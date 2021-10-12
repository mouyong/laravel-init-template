<?php

namespace ZhenMu\LaravelInitTemplate\DcatAdmin\Grid\Displayers;

class Tree extends \Dcat\Admin\Grid\Displayers\Tree
{
    public function getKey()
    {
        return $this->row->{$this->row->getIdName()};
    }
}