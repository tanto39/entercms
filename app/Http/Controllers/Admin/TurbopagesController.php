<?php

namespace App\Http\Controllers\Admin;

use App\Item;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TurbopagesController extends Controller
{
    /**
     * Display a listing of the resource
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('admin.turbopages.index');
    }

    /**
     * Generate rss
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generate(Request $request)
    {
        if (empty($_SERVER["DOCUMENT_ROOT"])) {
            $_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__));
        }

        $titleXML = '<rss xmlns:yandex="http://news.yandex.ru" xmlns:media="http://search.yahoo.com/mrss/" xmlns:turbo="http://turbo.yandex.ru" version="2.0"><channel>';
        $contentXML = '';
        $endXML = '</channel></rss>';

        $items = Item::with('category')->orderby('is_product')
            ->where('slug', '!=', '/')
            ->where('is_product', 0)
            ->select(['id', 'slug', 'title', 'is_product', 'category_id', 'updated_at', 'full_content'])
            ->get()->toArray();

        $categories = Category::select(['id', 'slug', 'title', 'catalog_section', 'parent_id', 'updated_at', 'full_content'])
            ->where('catalog_section', 0)
            ->orderby('catalog_section')->get()->toArray();

        // Add categories to xml
        if (!empty($categories)) {
            foreach ($categories as $key=>$category) {
                $url = '';

                if (($category['id'] === CATALOG_ID) || ($category['id'] === BLOG_ID)) {
                    if ($category['id'] === CATALOG_ID)
                        $url = HOST_PATH . CATALOG_SLUG;
                    elseif ($category['id'] === BLOG_ID)
                        $url = HOST_PATH . BLOG_SLUG;
                }
                else {
                    if ($category['parent_id'] == 0) {
                        $url = HOST_PATH . $category['slug'];
                    }
                    else {
                        if ($category['catalog_section'] == 1)
                            $url = HOST_PATH . CATALOG_SLUG . '/' . $category['slug'];
                        else
                            $url = HOST_PATH . BLOG_SLUG . '/' . $category['slug'];
                    }
                }

                $contentXML .= self::generateContentXml($url, $category);
            }
        }

        // Add items to xml
        if (!empty($items)) {
            foreach ($items as $key=>$item) {
                $url = '';

                if ($item['category_id'] == 0) {
                    $url = HOST_PATH . $item['slug'];
                }
                else {
                    if ($item['is_product'] == 1)
                        $url = HOST_PATH . CATALOG_SLUG . '/' . $item['category']['slug'] . '/' . $item['slug'];
                    else
                        $url = HOST_PATH . BLOG_SLUG . '/' . $item['category']['slug'] . '/' . $item['slug'];
                }

                $contentXML .= self::generateContentXml($url, $item);
            }
        }

        file_put_contents($_SERVER["DOCUMENT_ROOT"] . "public/turbopages.xml", $titleXML.$contentXML.$endXML);

        $request->session()->flash('success', 'RSS лента для турбостраниц обновлена');
        return redirect()->route('admin.turbopages.index');
    }

    /**
     * Generate content
     *
     * @param $url
     * @param $item
     * @return string
     */
    public static function generateContentXml ($url, $item)
    {
        $contentXML = '';
        $img = '';

        if (!empty($item['preview_img']))
            $img = '<img src="'.$item['preview_img'][0]['MIDDLE'].'" title="'.$item['title'].'" alt="'.$item['title'].'"';

        $contentXML = '<item turbo="true"><link>'
            . $url
            . '</link><turbo:content><![CDATA[<h1>'.$item['title'].'</h1>'
            . $img
            . $item['full_content']
            .']]></turbo:content></item>'
            ."\r\n";

        return $contentXML;
    }

}
