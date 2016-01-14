<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Validator;
use Schema;
use View;

class CategoryController extends Controller
{

    /**
    * Render dropdown nav
    *
    * @return view
    */
    public function index()
    {
        $categories = \App\Category::all()->toHierarchy();
        return view('categories.index', ['categories' => $categories]);
    }

    /**
    * Render information about node
    *
    * @param int $id
    * @return view
    */
    public function showNode($id)
    {
        $node = \App\Category::find($id);
        if ($node) {
            $resultArray = ['categories' => $node->getDescendantsAndSelf()->toHierarchy()];
        } else {
            $resultArray = ['categories' => ''];
        }
        return view('categories.index', $resultArray);
    }

    /**
    * Render children
    *
    * @param str $childrenIdStr
    * @return view
    */
    public function showChildren($childrenIdStr)
    {
        $resultArray = ['categories' => ''];
        if($childrenIdStr) {
            $childrenIdStr = str_replace("/children", "", $childrenIdStr);
            $childrenIdArray = explode("/", $childrenIdStr);
            $childrenIdArray = array_values(array_diff($childrenIdArray, array('')));

            if (count($childrenIdArray)) {
                $child = null;
                $rootId = $childrenIdArray[0];
                for($i = 1; $i < count($childrenIdArray); $i++) {
                    $child = \App\Category::where('parent_id', $rootId)
                            ->where('inner_id', $childrenIdArray[$i])->first();
                    if (!$child) {
                        break;
                    }
                    $rootId = $child->id;
                }
                $root = \App\Category::find($rootId);
                if ($root) {
                    $resultArray = ['categories' => $root->getDescendants()->toHierarchy()];
                }
            }
        }
        return view('categories.index', $resultArray);
    }

    /**
    * Render information about child
    *
    * @return view
    */
    public function showChild()
    {
        $numargs = func_num_args();
        $arg_list = func_get_args();
        $resultArray = ['categories' => ''];
        if($numargs > 1) {
            $rootId = $arg_list[0];
            $childrenIdStr = $arg_list[1];
            $childrenIdStr = str_replace("/children", "", $childrenIdStr);
            $childrenIdArray = explode("/", $childrenIdStr);
            $childrenIdArray = array_diff($childrenIdArray, array(''));

            $child = null;
            foreach($childrenIdArray as $childId) {
                $child = \App\Category::where('parent_id', $rootId)->where('inner_id', $childId)->first();
                if (!$child) {
                    break;
                }
                $rootId = $child->id;
            }
            if ($child) {
                $resultArray = ['categories' => $child->getDescendantsAndSelf()->toHierarchy()];
            }
        }
        return view('categories.index', $resultArray);
    }

    /**
    * Create new root node.
    *
    * @return JSON string
    */
    public function createNode()
    {
        $lastRootIndex = \App\Category::where('parent_id', null)->count()+1;
        $root = \App\Category::create(['name' => 'Root category '.$lastRootIndex]);
        return $this->buildResultMessage('OK', '', $root->toJson());
    }

    /**
    * Update the root node.
    *
    * @param  int  $id
    * @return JSON string
    */
    public function updateNode($id)
    {
        // validate
        $rules = array();
        $validator = Validator::make(Input::all(), $rules);

        // process
        if ($validator->fails()) {
            return $this->buildResultMessage('ERROR', implode($validator->errors()->all()), '');
        } else {
            // store
            $node = \App\Category::find($id);
            if ($node) {
                foreach(Input::all() as $key => $param) {
                    if (Schema::hasColumn('Categories', $key)) {
                        $node[$key] = $param;
                    }
                }
                $node->save();
                return $this->buildResultMessage('OK', "Node with id $id was updated", $node->toJson());
            } else {
                return $this->buildResultMessage('ERROR', "Node with id $id not found");
            }
        }
    }

    /**
    * Delete the root node
    *
    * @param  int  $id
    * @return JSON string
    */
    public function destroyNode($id)
    {
        $node = \App\Category::find($id);
        if ($node) {
            $node->delete();
            return $this->buildResultMessage('OK', "Node with id $id was deleted");
        } else {
            return $this->buildResultMessage('ERROR', "Node with id $id not found");
        }
    }

    /**
    * Add new child to the root node
    *
    * @param  str $childrenIdStr
    * @return JSON string
    */
    public function createChild($childrenIdStr)
    {
        if($childrenIdStr) {
            $childrenIdStr = str_replace("/children", "", $childrenIdStr);
            $childrenIdArray = explode("/", $childrenIdStr);
            $childrenIdArray = array_values(array_diff($childrenIdArray, array('')));

            if (count($childrenIdArray)) {
                $child = null;
                $rootId = $childrenIdArray[0];
                for($i = 1; $i < count($childrenIdArray); $i++) {
                    $child = \App\Category::where('parent_id', $rootId)
                            ->where('inner_id', $childrenIdArray[$i])->first();
                    if (!$child) {
                        break;
                    }
                    $rootId = $child->id;
                }
                $root = \App\Category::find($rootId);
                if ($root) {
                    $children = \App\Category::where('parent_id', $rootId);
                    $newChildId = $children->count() + 1;
                    $newChild = \App\Category::create(['name' => 'Child '.$newChildId, 'inner_id' => $newChildId]);
                    $newChild->makeChildOf($root);
                    if(count(Input::all())) {
                        foreach(Input::all() as $key => $param) {
                            if (Schema::hasColumn('Categories', $key)) {
                                $newChild[$key] = $param;
                            }
                        }
                        $newChild->save();
                    }
                    return $this->buildResultMessage('OK', '', $newChild->toJson());
                } else {
                    return $this->buildResultMessage('ERROR', "Node with id $root not found");
                }
            } else {
                return $this->buildResultMessage('ERROR', "Nodes id not received");
            }
        } else {
            return $this->buildResultMessage('ERROR', 'Nodes id not received');
        }
    }

    /**
    * Update the child node.
    *
    * @param  int  $rootId
    * @param  str  $childrenIdStr
    * @return JSON string
    */
    public function updateChild($rootId, $childrenIdStr)
    {
        // validate
        $rules = array();
        $validator = Validator::make(Input::all(), $rules);

        // process
        if ($validator->fails()) {
            return $this->buildResultMessage('ERROR', implode($validator->errors()->all()), '');
        } else {
            if($childrenIdStr) {
                $childrenIdStr = str_replace("/children", "", $childrenIdStr);
                $childrenIdArray = explode("/", $childrenIdStr);
                $childrenIdArray = array_values(array_diff($childrenIdArray, array('')));

                $child = null;
                foreach($childrenIdArray as $childId) {
                    $child = \App\Category::where('parent_id', $rootId)->where('inner_id', $childId)->first();
                    if (!$child) {
                        break;
                    }
                    $rootId = $child->id;
                }
                if ($child) {
                    foreach(Input::all() as $key => $param) {
                        if (Schema::hasColumn('Categories', $key)) {
                            $child[$key] = $param;
                        }
                    }
                    $child->save();
                    return $this->buildResultMessage('OK', "Child with id $rootId was updated", $child->toJson());
                } else {
                    return $this->buildResultMessage('ERROR', 'Child nodes not found');
                }
            } else {
                return $this->buildResultMessage('ERROR', 'Child nodes id not received');
            }
        }
    }

    /**
    * Delete the child node.
    *
    * @param  int  $rootId
    * @param  str  $childrenIdStr
    * @return JSON string
    */
    public function destroyChild($rootId, $childrenIdStr)
    {
        if($childrenIdStr) {
            $childrenIdStr = str_replace("/children", "", $childrenIdStr);
            $childrenIdArray = explode("/", $childrenIdStr);
            $childrenIdArray = array_values(array_diff($childrenIdArray, array('')));

            $child = null;
            foreach($childrenIdArray as $childId) {
                $child = \App\Category::where('parent_id', $rootId)->where('inner_id', $childId)->first();
                if (!$child) {
                    break;
                }
                $rootId = $child->id;
            }
            if ($child) {
                $child->delete();
                return $this->buildResultMessage('OK', "Child with id $rootId was deleted");
            } else {
                return $this->buildResultMessage('ERROR', 'Child with id $rootId not found');
            }
        } else {
            return $this->buildResultMessage('ERROR', 'Child nodes id not received');
        }
    }

    /**
    * Build result message in JSON format
    *
    * @param  string  $result
    * @param  string  $message
    * @param  string  $data
    * @return JSON string
    */
    private function buildResultMessage($result = 'OK', $message = '', $data = '""')
    {
        return '{"result": "'.$result.'", "message": "'.$message.'", "data": '.$data.'}';
    }

}
