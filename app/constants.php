<?php
// Site settings
define("ADMIN_EMAIL", 'tanto39@mail.ru');
define("HOST_PATH", 'http://entercms/');

// Pagination
define("PAGE_COUNT", 21);

// Slugs
define("BLOG_SLUG", 'blog');
define("CATALOG_SLUG", 'catalog');
define("REVIEWS_SLUG", 'reviews');

// Slugs
define("BLOG_TITLE", 'Контент');
define("CATALOG_TITLE", 'Каталог');

// Content id
define("CATALOG_ID", 5);
define("BLOG_ID", 1);

// Images
define("PREV_IMG_FULL_WIDTH", 1200);
define("PREV_IMG_MIDDLE_WIDTH", 450);
define("PREV_IMG_SMALL_WIDTH", 100);
define("PREV_IMG_FULL_PATH", 'images/shares/previews/');
define("FILE_LOAD_PATH", 'files/');

// Kinds of properties
define("PROP_KIND_CATEGORY", 1);
define("PROP_KIND_ITEM", 2);

// Types of properties
define("PROP_TYPE_NUM", 1);
define("PROP_TYPE_IMG", 3);
define("PROP_TYPE_FILE", 4);
define("PROP_TYPE_TEXT", 5);
define("PROP_TYPE_LIST", 6);
define("PROP_TYPE_CATEGORY_LINK", 7);
define("PROP_TYPE_ITEM_LINK", 8);

define("PROP_GROUP_NAME_ALL", 'Характеристики');
define("PROP_PRICE_ID", 14);

// Array of properties to sort in public catalog
define("AR_PROP_SORT", [PROP_PRICE_ID, 17]);

// Menu types
define("MENU_TYPE_CATEGORY", 1);
define("MENU_TYPE_ITEM", 2);
define("MENU_TYPE_REVIEWS", 3);
define("MENU_TYPE_LINK", 4);

// Default meta keys
define("META_TITLE", "EnterCMS");
define("META_KEY", "EnterCMS");
define("META_DESC", "EnterCMS");

// Contacts
define("COMPANY", "Курск-Строй-Про");
define("COMPANY_WHERE", "в Курске");
define("PHONE", "+7 (4712) 2-22-50");
define("ADDRESS", "г. Курск, ул. Пушкина, 8");
define("MAIL", "info@enterkursk.ru");
define("COMPANY_MAP", "<script defer async src=\"https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3AYWBlETvV_7SlsILrBhnqigPl7yE_Wk5d&amp;width=100%25&amp;height=358&amp;lang=ru_RU&amp;scroll=false\"></script>");

// Use regions ('Y', 'N')
define("USE_REGION", "N");

// Use catalog ('Y', 'N')
define("USE_CATALOG", "Y");