<?php

namespace ZhenMu\LaravelInitTemplate\DcatAdmin\Grid\Displayers;

class Actions extends \Dcat\Admin\Grid\Displayers\Actions
{
    protected function getViewLabel()
    {
        $label = trans('admin.show');

        return "<i title='{$label}' class=\"feather icon-eye grid-action-icon\"></i> $label &nbsp;";
    }

    protected function getEditLabel()
    {
        $label = trans('admin.edit');

        return "<i title='{$label}' class=\"feather icon-edit-1 grid-action-icon\"></i> $label &nbsp;";
    }

    protected function getDeleteLabel()
    {
        $label = trans('admin.delete');

        return "<i class=\"feather icon-trash grid-action-icon\" title='{$label}'></i> $label &nbsp;";
    }

}