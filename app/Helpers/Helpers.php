<?php

namespace App\Helpers;

class Helper
{

    public static function renderNode($node, $subId = 0)
    {
        $html = '';
        $dataParent = 'data-parent="#container"';
        if ($subId) {
            $dataParent = 'data-parent="#node'.$subId.'"';
        }
        if($node->isLeaf()) {
            $html .= '<a href="#" class="list-group-item" '.$dataParent.'data-href="#node'.$node->id.'">'
                    .$node->name.'<a href="#modal" role="button" data-toggle="modal" data-id="'
                    .$node->id.'"><i class="fa fa-plus"></i></a></a>';
        } else {
            $html .= '<a href="#node'.$node->id.'" class="list-group-item" data-toggle="collapse" '
                    .$dataParent.'>'.$node->name.'<i class="fa fa-caret-down"></i><a href="#modal"'.
                    'role="button" data-toggle="modal" data-id="'.$node->id.'"><i class="fa fa-plus"></i></a></a>';
            $html .= '<div class="collapse list-group-submenu" id="node'.$node->id.'">';

            foreach($node->children as $child) {
                $html .= Helper::renderNode($child, $node->id);
            }

            $html .= '</div>';

        }
        return $html;
    }

}
