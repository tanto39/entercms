<?php

namespace App\Http\Controllers\Admin;

use App\Item;
use App\Category;
use App\PropEnum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class YandexMarketController extends Controller
{
    /**
     * Display a listing of the resource
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('admin.yandexmarket.index');
    }

    /**
     * Generate yml
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generate(Request $request)
    {
        if (empty($_SERVER["DOCUMENT_ROOT"])) {
            $_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__));
        }

        $ymlXML = '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n" .
            '<yml_catalog date="'.date("Y-m-d H:i:s").'"><shop><name>'.COMPANY.'</name><company>'.COMPANY.'</company><url>'.HOST_PATH.'</url>
                <currencies>
                    <currency id="RUR" rate="1"/>
                    <currency id="USD" rate="60"/>
                </currencies>
                <categories>
                    <category id="1">Бытовая техника</category>
                    <category id="10" parentId="1">Мелкая техника для кухни</category>
                </categories>
                <delivery-options>
                      <option cost="300" days="0" order-before="12"/>
                </delivery-options>
                <offers>
        '
            . "\r\n";
        $ymlTXT = '';
        $ymlEnd = '</offers></shop></yml_catalog>';

        $items = Item::with('category')->orderby('is_product')
            ->where('slug', '!=', '/')
            ->where('published', 1)
            ->where('is_product', 1)
            ->get()->toArray();

        $categories = Category::select(['id', 'slug', 'catalog_section', 'parent_id', 'updated_at'])
            ->orderby('catalog_section')->get()->toArray();

        // Add items
        if (!empty($items)) {
            foreach ($items as $key=>$item) {
                $url = '';
                $price = 0;
                $picture = '';
                $properties = [];
                $params = '';

                $url = HOST_PATH . CATALOG_SLUG . '/' . $item['category']['slug'] . '/' . $item['slug'];
                $properties = unserialize($item['properties']);
                $price = $properties[PROP_GROUP_NAME_ALL][PROP_PRICE_ID]['value'];

                // Param tag
                foreach ($properties as $propertyGroupName=>$propertyGroup) {
                    foreach ($propertyGroup as $propertyId => $property) {
                        if ($propertyId != PROP_PRICE_ID) {
                            if (($property['type'] == PROP_TYPE_NUM || $property['type'] == PROP_TYPE_TEXT) && !empty($property['value'])) {
                                $params .= '<param name="'.$property['title'].'">'.$property['value'].'</param>';
                            }
                            else if($property['type'] == PROP_TYPE_LIST) {
                                foreach ($property['value'] as $keyProp => $propListId) {
                                    $propListArray = PropEnum::where('id', $propListId)
                                        ->select(['id', 'title'])
                                        ->get()
                                        ->toArray();

                                    if(!empty($propListArray))
                                        $params .= '<param name="'.$property['title'].'">'.$propListArray[0]['title'].'</param>';
                                }
                            }
                        }
                    }
                }

                if(!empty($item['preview_img']))
                    $picture = unserialize($item['preview_img']);
                if(!empty($picture[0]["FULL"]))
                    $picture = HOST_PATH.PREV_IMG_FULL_PATH.$picture[0]["FULL"];

                $ymlTXT .= "\t" . '<offer id="'.$item["id"].'">
                    <name>'.$item["title"].'</name>
                    <url>'.$url.'</url>
                    <price>'.$price.'</price>
                    <currencyId>RUR</currencyId>
                    <categoryId>'.$item['category_id'].'</categoryId>
                    <picture>'.$picture.'</picture>
                    <pickup>true</pickup><delivery>true</delivery>
                    <description><![CDATA['.$item["full_content"].']]></description>'.$params
                . "</offer>\r\n";
            }
        }

        file_put_contents($_SERVER["DOCUMENT_ROOT"] . "public/yandexmarket.yml", $ymlXML.$ymlTXT.$ymlEnd);

        $request->session()->flash('success', 'Выгрузка сгенерирована');
        return redirect()->route('admin.yandexmarket.index');
    }

}
