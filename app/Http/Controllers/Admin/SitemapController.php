<?php

namespace App\Http\Controllers\Admin;

use App\Item;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SitemapController extends Controller
{
    /**
     * Display a listing of the resource
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('admin.sitemap.index');
    }

    /**
     * Generate sitemap
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generate(Request $request)
    {
        if (empty($_SERVER["DOCUMENT_ROOT"])) {
            $_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__));
        }

        $sitemapXML = '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\r\n";
        $sitemapTXT = '';
        $sitemapEnd = '</urlset>';

        $mainPage = Item::where('slug', '/')->select(['id', 'slug', 'updated_at'])->get()->toArray()[0];

        $items = Item::with('category')->orderby('is_product')
            ->where('slug', '!=', '/')
            ->where('published', 1)
            ->select(['id', 'slug', 'is_product', 'category_id', 'updated_at'])->get()->toArray();

        $categories = Category::select(['id', 'slug', 'catalog_section', 'parent_id', 'updated_at'])
            ->orderby('catalog_section')->get()->toArray();

        // Add main page
        $sitemapTXT .= "\t" . '<url><loc>' . HOST_PATH . '</loc><lastmod>' . explode(" ", $mainPage['updated_at'])[0] . '</lastmod><changefreq>monthly</changefreq><priority>0.5</priority></url>' . "\r\n";

        // Add categories to sitemap
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

                $sitemapTXT .= "\t" . '<url><loc>' . $url . '</loc><lastmod>' . explode(" ", $category['updated_at'])[0] . '</lastmod><changefreq>weekly</changefreq><priority>0.5</priority></url>' . "\r\n";
            }
        }

        // Add items to sitemap
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

                $sitemapTXT .= "\t" . '<url><loc>' . $url . '</loc><lastmod>' . explode(" ", $item['updated_at'])[0] . '</lastmod><changefreq>monthly</changefreq><priority>0.5</priority></url>' . "\r\n";
            }
        }

        file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/public/sitemap.xml", $sitemapXML.$sitemapTXT.$sitemapEnd);

        $request->session()->flash('success', 'Карта сайта обновлена');
        return redirect()->route('admin.sitemap.index');
    }

}
