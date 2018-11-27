<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/20
 * Time: 20:03
 */
namespace common\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\base\Widget;
use yii\data\Pagination;
use yii\widgets\LinkPager;

/**
 * LinkPager displays a list of hyperlinks that lead to different pages of target.
 *
 * LinkPager works with a [[Pagination]] object which specifies the total number
 * of pages and the current page number.
 *
 * Note that LinkPager only generates the necessary HTML markups. In order for it
 * to look like a real pager, you should provide some CSS styles for it.
 * With the default configuration, LinkPager should look good using Twitter Bootstrap CSS framework.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class LinkPagerNew extends LinkPager
{
    /**
     * @var Pagination the pagination object that this pager is associated with.
     * You must set this property in order to make LinkPager work.
     */
    public $pagination;
    /**
     * @var array HTML attributes for the pager container tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = ['class' => 'pagination'];
    /**
     * @var array HTML attributes for the link in a pager container tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $linkOptions = ['class' => 'page-link'];
    /**
     * @var string the CSS class for the each page button.
     * @since 2.0.7
     */
    public $pageCssClass = null;
    /**
     * @var string the CSS class for the "first" page button.
     */
    public $firstPageCssClass = 'first';
    /**
     * @var string the CSS class for the "last" page button.
     */
    public $lastPageCssClass = 'last';
    /**
     * @var string the CSS class for the "previous" page button.
     */
    public $prevPageCssClass = 'prev';
    /**
     * @var string the CSS class for the "next" page button.
     */
    public $nextPageCssClass = 'next';
    /**
     * @var string the CSS class for the active (currently selected) page button.
     */
    public $activePageCssClass = 'active';
    /**
     * @var string the CSS class for the disabled page buttons.
     */
    public $disabledPageCssClass = 'disabled';
    /**
     * @var integer maximum number of page buttons that can be displayed. Defaults to 10.
     */
    public $maxButtonCount = 10;
    /**
     * @var string|boolean the label for the "next" page button. Note that this will NOT be HTML-encoded.
     * If this property is false, the "next" page button will not be displayed.
     */
    public $nextPageLabel = '&raquo;';
    /**
     * @var string|boolean the text label for the previous page button. Note that this will NOT be HTML-encoded.
     * If this property is false, the "previous" page button will not be displayed.
     */
    public $prevPageLabel = '&laquo;';
    /**
     * @var string|boolean the text label for the "first" page button. Note that this will NOT be HTML-encoded.
     * If it's specified as true, page number will be used as label.
     * Default is false that means the "first" page button will not be displayed.
     */

    public $CountPageLabel = '共 {countPage} 页 共 {totalCount} 条记录';
    public $CountPageClass = 'page-number';

    public $firstPageLabel = false;
    /**
     * @var string|boolean the text label for the "last" page button. Note that this will NOT be HTML-encoded.
     * If it's specified as true, page number will be used as label.
     * Default is false that means the "last" page button will not be displayed.
     */
    public $lastPageLabel = false;
    /**
     * @var boolean whether to register link tags in the HTML header for prev, next, first and last page.
     * Defaults to `false` to avoid conflicts when multiple pagers are used on one page.
     * @see http://www.w3.org/TR/html401/struct/links.html#h-12.1.2
     * @see registerLinkTags()
     */
    public $registerLinkTags = false;
    /**
     * @var boolean Hide widget when only one page exist.
     */
    public $hideOnSinglePage = true;

    public  $internalPage = true;

    /**
     *是否显示自定义跳转
     *
     */
    public $goPageLabel = false;

    /**
     *@var array.options about the goPageLabel(input)
     *goPageLabelOptions => [
     *       'class' =>
     *       'data-num' =>
     *       'style' =>
     *   ]
     *
     */
    public $goPageLabelOptions = [];

    /**
     *自定义跳转按钮
     *
     */
    public $goButtonLable = false;

    /**
     *@var array.options about the goButton
     *
     */
    public $goButtonLableOptions = [];

    public $goPageLinkOptions = [];

    /**
     * 是否显示每页显示记录数
     */
    public $showPageSize = false;
    /**
     * 每页显示记录数标签
     */
    public $pageSizeLabelOptions = [];

    /**
     * Initializes the pager.
     */
    public function init()
    {
        if ($this->pagination === null) {
            throw new InvalidConfigException('The "pagination" property must be set.');
        }
    }

    /**
     * Executes the widget.
     * This overrides the parent implementation by displaying the generated page buttons.
     */
    public function run()
    {
        if ($this->registerLinkTags) {
            $this->registerLinkTags();
        }
        echo $this->renderPageButtons();
    }

    /**
     * Registers relational link tags in the html header for prev, next, first and last page.
     * These links are generated using [[\yii\data\Pagination::getLinks()]].
     * @see http://www.w3.org/TR/html401/struct/links.html#h-12.1.2
     */
    protected function registerLinkTags()
    {
        $view = $this->getView();
        foreach ($this->pagination->getLinks() as $rel => $href) {
            $view->registerLinkTag(['rel' => $rel, 'href' => $href], $rel);
        }
    }

    /**
     * Renders the page buttons.
     * @return string the rendering result
     */
    protected function renderPageButtons()
    {
        $pageCount = $this->pagination->getPageCount();
        $totalCount = $this->pagination->totalCount;
        $pageSize = $this->pagination->pageSize;
        if ($pageCount < 2 && $this->hideOnSinglePage) {
            return '';
        }

        $buttons = [];
        $currentPage = $this->pagination->getPage();

        // first page
        $firstPageLabel = $this->firstPageLabel === true ? '1' : $this->firstPageLabel;
        if ($firstPageLabel !== false) {
            $buttons[] = $this->renderPageButton($firstPageLabel, 0, $this->firstPageCssClass, $currentPage <= 0, false);
        }

        // prev page
        if ($this->prevPageLabel !== false) {
            if (($page = $currentPage - 1) < 0) {
                $page = 0;
            }
            $buttons[] = $this->renderPageButton($this->prevPageLabel, $page, $this->prevPageCssClass, $currentPage <= 0, false);
        }

        // internal pages
        if($this->internalPage){
            list($beginPage, $endPage) = $this->getPageRange();
            for ($i = $beginPage; $i <= $endPage; ++$i) {
                $buttons[] = $this->renderPageButton($i + 1, $i, null, false, $i == $currentPage);
            }
        }


        // next page
        if ($this->nextPageLabel !== false) {
            if (($page = $currentPage + 1) >= $pageCount - 1) {
                $page = $pageCount - 1;
            }
            $buttons[] = $this->renderPageButton($this->nextPageLabel, $page, $this->nextPageCssClass, $currentPage >= $pageCount - 1, false);
        }

        // last page
        $lastPageLabel = $this->lastPageLabel === true ? $pageCount : $this->lastPageLabel;
        if ($lastPageLabel !== false) {
            $buttons[] = $this->renderPageButton($lastPageLabel, $pageCount - 1, $this->lastPageCssClass, $currentPage >= $pageCount - 1, false);
        }

        if ($this->CountPageLabel !== false && $pageCount) {
            $CountPageLabel = str_replace(['{countPage}', '{totalCount}'], [$pageCount, $totalCount], $this->CountPageLabel);
            $buttons[] = Html::tag('li', Html::tag('span', $CountPageLabel,array('class'=>'page-link')), array('class' => $this->CountPageClass));
        }

        //gopage
        if ($this->goPageLabel) {
            $input = Html::input('number',$this->pagination->pageParam,$currentPage+1,array_merge([
                'min' => 1,
                'max' => $pageCount,
                'style' => 'height:38px;width:64px;display:inline-block;margin:0 3px 0 3px',
                'class' => 'form-control',
            ],$this->goPageLabelOptions));

            $buttons[] = Html::tag('li',$input,[]);
        }

        // gobuttonlink
        if ($this->goPageLabel) {
            $buttons[] = Html::tag('button',$this->goButtonLable ? $this->goButtonLable : '跳转',['style' => 'height:38px;display:inline-block;',
                'class' => 'btn btn-outline-primary go-page']);
        }

        //pagesize
        if($this->showPageSize !== false){
            $input = Html::input('number',$this->pagination->pageSizeParam,$pageSize,array_merge([
                'min' => 1,
                'max' => 100,
                'style' => 'height:30px;width:64px;display:inline-block;margin:0 3px 0 3px',
                'class' => 'form-control page-size-input',
            ],$this->pageSizeLabelOptions));

            $pageSizeInput = "每页显示".$input.'条';

            $buttons[] = Html::tag('li',$pageSizeInput,[]);
        }

        return Html::tag('ul', implode("\n", $buttons), $this->options);
    }

    /**
     * Renders a page button.
     * You may override this method to customize the generation of page buttons.
     * @param string $label the text label for the button
     * @param integer $page the page number
     * @param string $class the CSS class for the page button.
     * @param boolean $disabled whether this page button is disabled
     * @param boolean $active whether this page button is active
     * @return string the rendering result
     */
    protected function renderPageButton($label, $page, $class, $disabled, $active)
    {
        $options = ['class' => empty($class) ? $this->pageCssClass : $class];
        if ($active) {
            Html::addCssClass($options, $this->activePageCssClass);
        }
        if ($disabled) {
            Html::addCssClass($options, $this->disabledPageCssClass);

            return Html::tag('li', Html::tag('span', $label,array('class'=>'page-link')), $options);
        }
        $linkOptions = $this->linkOptions;
        $linkOptions['data-page'] = $page;

        return Html::tag('li', Html::a($label, $this->pagination->createUrl($page), $linkOptions), $options);
    }

    /**
     * @return array the begin and end pages that need to be displayed.
     */
    protected function getPageRange()
    {
        $currentPage = $this->pagination->getPage();
        $pageCount = $this->pagination->getPageCount();

        $beginPage = max(0, $currentPage - (int) ($this->maxButtonCount / 2));
        if (($endPage = $beginPage + $this->maxButtonCount - 1) >= $pageCount) {
            $endPage = $pageCount - 1;
            $beginPage = max(0, $endPage - $this->maxButtonCount + 1);
        }

        return [$beginPage, $endPage];
    }
}