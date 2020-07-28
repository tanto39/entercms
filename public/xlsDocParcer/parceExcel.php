<?php
namespace Enterkursk\Parce;

use SimpleXLS;

class parceExcel
{
    public static function execute($period = '2020', $files = [], $summ = [], $date = '')
    {
        $result = '';
        $rows = [];
        $topSheet = '<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <Author>Microsoft Corporation</Author>
  <LastAuthor>Пользователь Windows</LastAuthor>
  <LastPrinted>2020-07-27T17:23:21Z</LastPrinted>
  <Created>1996-10-08T23:32:33Z</Created>
  <LastSaved>2019-02-11T22:11:27Z</LastSaved>
  <Version>12.00</Version>
 </DocumentProperties>
 <OfficeDocumentSettings xmlns="urn:schemas-microsoft-com:office:office">
  <AllowPNG/>
 </OfficeDocumentSettings>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>11460</WindowHeight>
  <WindowWidth>19200</WindowWidth>
  <WindowTopX>0</WindowTopX>
  <WindowTopY>0</WindowTopY>
  <RefModeR1C1/>
  <ProtectStructure>False</ProtectStructure>
  <ProtectWindows>False</ProtectWindows>
  <DisplayInkNotes>False</DisplayInkNotes>
 </ExcelWorkbook>
 <Styles>
  <Style ss:ID="Default" ss:Name="Normal">
   <Alignment ss:Vertical="Bottom"/>
   <Borders/>
   <Font ss:FontName="Arial" x:CharSet="204" x:Family="Swiss"/>
   <Interior/>
   <NumberFormat/>
   <Protection/>
  </Style>
  <Style ss:ID="s63">
   <Alignment ss:Vertical="Center" ss:WrapText="1"/>
   <Borders>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font ss:FontName="Arial" x:CharSet="204" x:Family="Swiss" ss:Size="11"/>
  </Style>
 </Styles>
 <Worksheet ss:Name="Лист1">
  <Table ss:ExpandedColumnCount="1" x:FullColumns="1"
   x:FullRows="1">
   <Column ss:AutoFitWidth="0" ss:Width="500.25"/>';

        $bottomSheet = '  </Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <PageSetup>
    <Header x:Margin="0.51181102362204722"/>
    <Footer x:Margin="0.51181102362204722"/>
    <PageMargins x:Bottom="0.38425196850393704" x:Left="0.54803149606299213"
     x:Right="0.54803149606299213" x:Top="0.38425196850393704"/>
   </PageSetup>
   <Print>
    <ValidPrinterInfo/>
    <PaperSizeIndex>9</PaperSizeIndex>
    <HorizontalResolution>600</HorizontalResolution>
    <VerticalResolution>600</VerticalResolution>
   </Print>
   <PageBreakZoom>60</PageBreakZoom>
   <Selected/>
   <Panes>
    <Pane>
     <Number>3</Number>
     <ActiveRow>4</ActiveRow>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
</Workbook>';

        $titleCell = '                                                          СПРАВКА №______';

        $endCell = '&#10; &#10;И.о. начальника ОСО Пристенского района _____________ Г.В.Лашина';

        $contentCell = '';

        $summ = self::changeMonthSumArray($summ);
        $sumVT = array_sum($summ['vt']);
        $sumTT = array_sum($summ['tt']);
        $sumVV = array_sum($summ['vv']);

        $monthVT = '';
        foreach ($summ['vt'] as $monthName=>$monthSum) {
            $monthVT .= $monthName.' - '.$monthSum.' руб.    ';
        }

        $monthTT = '';
        foreach ($summ['tt'] as $monthName=>$monthSum) {
            $monthTT .= $monthName.' - '.$monthSum.' руб.    ';
        }

        $monthVV = '';
        foreach ($summ['vv'] as $monthName=>$monthSum) {
            $monthVV .= $monthName.' - '.$monthSum.' руб.    ';
        }

        $inputFile = self::loadFile($files);

        if ( $xls = SimpleXLS::parse($inputFile) ) {
            $rows = $xls->rows();
            unset($rows[0]);
        } else {
            echo SimpleXLS::parseError();
        }

        foreach ($rows as $row) {

            if ($row[3] == 'вт') {
                $contentCell .= '<Row ss:AutoFitHeight="0" ss:Height="191">
    <Cell ss:StyleID="s63"><Data ss:Type="String">'.$titleCell.'&#10;&#10;Дана '.$row[0].', '.$row[1].' проживающий (ая) '.$row[2].' в том, что она (он) состоит на учете в ОСО Пристенского района как получатель ежемесячной денежной выплаты ветеран труда.&#10;Размер ЕДВ за период '.$period.' составил - '.$sumVT.' рублей&#10; '.$monthVT.' &#10; &#10;Дата выдачи справки '.$date.$endCell.'.</Data></Cell>
   </Row>';
            }
            elseif ($row[3] == 'тт') {
                $contentCell .= '<Row ss:AutoFitHeight="0" ss:Height="191">
    <Cell ss:StyleID="s63"><Data ss:Type="String">'.$titleCell.'&#10;&#10;Дана '.$row[0].', '.$row[1].' проживающий (ая) '.$row[2].' в том, что она (он) состоит на учете в ОСО Пристенского района как получатель ежемесячной денежной выплаты труженик тыла.&#10;Размер ЕДВ за период '.$period.' составил - '.$sumTT.' рублей&#10; '.$monthTT.' &#10; &#10;Дата выдачи справки '.$date.$endCell.'.</Data></Cell>
   </Row>';
            }
            elseif ($row[3] == 'вв') {
                $contentCell .= '<Row ss:AutoFitHeight="0" ss:Height="191">
    <Cell ss:StyleID="s63"><Data ss:Type="String">'.$titleCell.'&#10;&#10;Дана '.$row[0].', '.$row[1].' проживающий (ая) '.$row[2].' в том, что она (он) состоит на учете в ОСО Пристенского района как получатель ежемесячной денежной выплаты компенсация взамен прод.товаров.&#10;Размер ЕДВ за период '.$period.' составил - '.$sumVV.' рублей&#10; '.$monthVV.' &#10; &#10;Дата выдачи справки '.$date.$endCell.'.</Data></Cell>
   </Row>';
            }
            else {
                $contentCell .= '<Row ss:AutoFitHeight="0" ss:Height="161">
    <Cell ss:StyleID="s63"><Data ss:Type="String">'.$titleCell.'&#10;&#10;Дана '.$row[0].', '.$row[1].' проживающий (ая) '.$row[2].' в том, что она (он) не состоит на учете в ОСО Пристенского района как получатель ежемесячной денежной выплаты&#10;Размер ЕДВ за период '.$period.' составил -0 рублей&#10;Дата выдачи справки '.$date.$endCell.'.</Data></Cell>
   </Row>';
            }

            $contentCell .= $endCell;
        }


        $result = $topSheet.$contentCell.$bottomSheet;

        $hashName = "справки-".substr(md5(openssl_random_pseudo_bytes(20)),10).".xml";

        file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/public/xlsDocParcer/files/".$hashName, $result);

        return $hashName;
    }

    /**
     * Load input file
     *
     * @param $files
     * @return string
     */
    public static function loadFile($files)
    {
        $newname = "";

        if((!empty($files["load-file"])) && ($files['load-file']['error'] == 0)) {
            $filename = $files['load-file']['name'];

            // путь для сохранения файла
            $newname = $_SERVER["DOCUMENT_ROOT"] . "/public/xlsDocParcer/files/".$filename;

            if (move_uploaded_file($files['load-file']['tmp_name'], $newname)) {
                $data = $files['load-file'];
            } else {
                $data['errors'] = "Во время загрузки файла произошла ошибка";
            }
        }

        return $newname;
    }

    /**
     * @param $summ
     * @return mixed
     */
    public static function changeMonthSumArray($summ)
    {
        foreach ($summ as $priznak=>$values) {
            foreach ($values as $key=>$value) {
                if(empty($value)) {
                    unset($summ[$priznak][$key]);
                }
                else {
                    $summ[$priznak][$key] = htmlspecialchars(trim(stripcslashes(strip_tags($value))));
                }
            }

        }

        return $summ;
    }
}
