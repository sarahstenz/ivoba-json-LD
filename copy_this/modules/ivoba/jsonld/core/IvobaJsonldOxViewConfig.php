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

class IvobaJsonldOxViewConfig extends IvobaJsonldOxViewConfig_parent
{
    /**
     * @return mixed|null|string|void
     */
    public function getJsonLd()
    {
        $cfg          = oxRegistry::getConfig();
        $jsonLd       = [];
        $organization = [];
        $webSite      = [];
        if ($cfg->getConfigParam('ivoba_JsonLdEnableMarketingDetails') && $this->getActionClassName() === 'start') {
            $organization = array_merge($organization, $this->getMarketingDetails());
        }
        if ($cfg->getConfigParam('ivoba_JsonLdEnableSearch') && $this->getActionClassName() === 'start') {
            $webSite = array_merge($webSite, $this->getSearch());
        }
        if ($cfg->getConfigParam('ivoba_JsonLdEnableContactDetails') && $this->getActionClassName() === 'start') {
            $organization = array_merge($organization, $this->getContactDetails());
        }
        if ($cfg->getConfigParam('ivoba_JsonLdEnableBreadCrumbs')) {
            $breadCrumbs = $this->getBreadCrumbs();
            if ($breadCrumbs) {
                $jsonLd[] = $breadCrumbs;
            }
        }
        if ($cfg->getConfigParam('ivoba_JsonLdEnableLists') && $this->getActionClassName() === 'alist') {
            $lists = $this->getLists();
            if ($lists) {
                $jsonLd[] = $lists;
            }
        }

        if ($organization) {
            $organizationBase = [
                '@context' => 'http://schema.org',
                '@type'    => 'Organization',
                '@id'      => '#organization',
                'url'      => $this->getBaseDir(),
            ];
            $jsonLd[]         = array_merge($organizationBase, $organization);
        }

        if ($webSite) {
            $webSiteBase = [
                '@context' => 'http://schema.org',
                '@type'    => 'WebSite',
                '@id'      => '#website',
                'url'      => $this->getBaseDir(),
                'name'     => $cfg->getActiveShop()->oxshops__oxname->value,
            ];
            $jsonLd[]    = array_merge($webSiteBase, $webSite);
        }

        if ($jsonLd) {

            return json_encode($jsonLd);
        }

        return null;
    }

    /**
     * @return array
     */
    protected function getMarketingDetails()
    {
        $cfg   = oxRegistry::getConfig();
        $array = ['name' => $cfg->getActiveShop()->oxshops__oxcompany->value];
        if ($cfg->getConfigParam('ivoba_JsonLdSocialLinks')) {
            $array['sameAs'] = explode(',', $cfg->getConfigParam('ivoba_JsonLdSocialLinks'));
        }

        $array['logo'] = $this->getImageUrl($this->getShopLogo());
        if ($cfg->getConfigParam('ivoba_JsonLdLogo')) {
            $array['logo'] = $cfg->getConfigParam('ivoba_JsonLdLogo');
        }

        return $array;
    }

    /**
     * @return array
     */
    protected function getSearch()
    {
        return [
            'potentialAction' => [
                '@type'       => 'SearchAction',
                'target'      => $this->getBaseDir().'?cl=search&searchparam={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getBreadCrumbs()
    {
        $json        = [];
        $breadCrumbs = oxRegistry::getConfig()->getActiveView()->getBreadCrumb();
        if ($breadCrumbs) {
            $items = [];
            foreach ($breadCrumbs as $key => $breadCrumb) {
                $items[] = [
                    '@type'    => 'ListItem',
                    '@id'      => $breadCrumb['link'],
                    'position' => $key + 1,
                    'name'     => $breadCrumb['title'],
                ];
            }
            $json = [
                '@context'        => 'http://schema.org',
                '@type'           => 'BreadcrumbList',
                'itemListElement' => $items,
            ];
        }

        return $json;
    }

    /**
     * @return array
     */
    protected function getContactDetails()
    {
        $cfg = oxRegistry::getConfig();
        $tel = $cfg->getActiveShop()->oxshops__oxtelefon->value;
        //expects format +1-401-555-1212
        if (substr($tel, 0, 2) === '00') {
            $tel = substr_replace($tel, '+', 0, 2);
        }

        return [
            'contactPoint' => [
                '@type'       => 'ContactPoint',
                'telephone'   => $tel,
                'contactType' => 'customer service',
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getLists()
    {
        $json = [];
        $list = oxRegistry::getConfig()->getActiveView()->getArticleList();
        if ($list) {
            $items = [];
            $i     = 1;
            foreach ($list as $item) {
                $items[] = [
                    '@type'    => 'ListItem',
                    'url'      => $item->getLink(),
                    'position' => $i, // offset by pagination ?
                ];
                $i++;
            }
            $json = [
                '@context'        => 'http://schema.org',
                '@type'           => 'ItemList',
                'itemListElement' => $items,
                'numberOfItems'   => (int)oxRegistry::getConfig()->getActiveView()->getArticleCount(),
                //todo itemListOrder
            ];
        }

        return $json;
    }
}