<?php
/* Please retain this copyright header in all versions of the software
 *
 * Copyright (C) 2017 Ivo Bathke
 *
 * It is published under the MIT Open Source License.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

$sMetadataVersion = '1.1';
$aModule          = [
    'id'          => 'ivoba_jsonld',
    'title'       => '<strong>Ivo Bathke</strong>:  <i>JSON-LD Structured Data</i>',
    'description' => 'Integrate Structured Data of your shop in your shop. Google and other search engines will like it :)<br> 
<a href="https://developers.google.com/search/docs/guides/intro-structured-data
" target="_blank""">https://developers.google.com/search/docs/guides/intro-structured-data</a>',
    'thumbnail'   => 'ivoba-oxid.png',
    'version'     => '1.0',
    'author'      => 'Ivo Bathke',
    'email'       => 'ivo.bathke@gmail.com',
    'url'         => 'https://oxid.ivo-bathke.name#json-ld',
    'extend'      => ['oxviewconfig' => 'ivoba/jsonld/core/IvobaJsonldOxViewConfig'],
    'blocks'      => [
        array(
            'template' => 'layout/base.tpl',
            'block'    => 'head_css', // we add it right after the css
            'file'     => '/views/blocks/jsonld_structured_data.tpl',
        ),
    ],
    'settings'    => [
        ['group' => 'ivoba_main', 'name' => 'ivoba_JsonLdEnableMarketingDetails', 'type' => 'bool', 'value' => true],
        ['group' => 'ivoba_main', 'name' => 'ivoba_JsonLdEnableSearch', 'type' => 'bool', 'value' => true],
        ['group' => 'ivoba_main', 'name' => 'ivoba_JsonLdEnableBreadCrumbs', 'type' => 'bool', 'value' => true],
        ['group' => 'ivoba_main', 'name' => 'ivoba_JsonLdEnableContactDetails', 'type' => 'bool', 'value' => true],
        ['group' => 'ivoba_main', 'name' => 'ivoba_JsonLdEnableLists', 'type' => 'bool', 'value' => true],
        ['group' => 'ivoba_marketing', 'name' => 'ivoba_JsonLdSocialLinks', 'type' => 'str', 'value' => ''],
        ['group' => 'ivoba_marketing', 'name' => 'ivoba_JsonLdLogo', 'type' => 'str', 'value' => ''],
    ],
];
