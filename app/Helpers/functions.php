<?php

if (! function_exists('getTree')) {
    /**
     * Tree
     */
    function getTree($array, $parent_id = 0, $level = 0, $levelLimit = null, $idName = 'id', $parentIDName = 'parent_id', $childrenName = 'children')
    {
        $list = [];

        foreach ($array as $key => $value) {
            if ($value[$parentIDName] == $parent_id) {
                $value['level'] = $level;
                unset($array[$key]);

                if ($levelLimit == null || $level < $levelLimit) {
                    $temp = getTree($array, $value[$idName], $level + 1, $levelLimit, $idName, $parentIDName, $childrenName);

                    if ($temp) {
                        $value[$childrenName] = $temp;
                    }
                }

                $list[] = $value;
            }
        }

        return $list;
    }
}
