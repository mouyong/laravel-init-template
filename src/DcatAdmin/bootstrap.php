<?php

use Dcat\Admin\Admin;
use Dcat\Admin\Grid;
use Dcat\Admin\Form;
use Dcat\Admin\Support\Helper;

/**
 * Dcat-admin - admin builder based on Laravel.
 * @author jqh <https://github.com/jqhph>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 *
 * extend custom field:
 * Dcat\Admin\Form::extend('php', PHPEditor::class);
 * Dcat\Admin\Grid\Column::extend('php', PHPEditor::class);
 * Dcat\Admin\Grid\Filter::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

if (file_exists(public_path('css/app.css'))) {
    admin_css(['/css/app.css']);
}

if (file_exists(public_path('js/app.js'))) {
    admin_js(['/js/app.js']);
}

Grid::resolving(function (Grid $grid) {
    $grid->enableDialogCreate();
    $grid->showQuickEditButton();

    $grid->disableRefreshButton();
    $grid->disableFilterButton();
    $grid->disableCreateButton();

    $grid->disableRowSelector();

    $grid->disableActions();
    $grid->disableViewButton();
    $grid->disableEditButton();
    $grid->disableDeleteButton();
});

Grid\Column::macro('customTree', function (bool $showAll = false, bool $sortable = true, $defaultParentId = null) {
    $this->grid->model()->enableTree($showAll, $sortable, $defaultParentId);

    $this->grid->listen(Grid\Events\Fetching::class, function () use ($showAll) {
        if ($this->grid->model()->getParentIdFromRequest()) {
            $this->grid->disableFilter();
            $this->grid->disableToolbar();

            if ($showAll) {
                $this->grid->disablePagination();
            }
        }
    });

    return $this->displayUsing(\ZhenMu\LaravelInitTemplate\DcatAdmin\Grid\Displayers\Tree::class);
});

Grid\Filter::resolving(function (Grid\Filter $filter) {
    return $filter->panel();
});

Form::resolving(function (Form $form) {
    $form->disableEditingCheck();

    $form->disableCreatingCheck();

    $form->disableViewCheck();

    $form->tools(function (Form\Tools $tools) {
        $tools->disableDelete();
        $tools->disableView();
        $tools->disableList();
    });
});