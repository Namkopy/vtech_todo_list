<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Database\Query;
use Kreait\Firebase\Database\Query\Sorter\OrderByChild;
use Kreait\Firebase\Database\Query\Sorter\OrderByValue;

class todoController extends Controller
{
    private $database;
    private $tableName;
    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->tableName = 'todolist';
    }

    // show todo list
    public function show(Request $req)
    {
        // dd($req->all(), $req->search);
        if (isset($req->search)) {

            // list and search by todo
            $getData = $this->database->getReference($this->tableName)
                ->orderByChild('todo')
                ->equalTo($req->search)
                ->getValue();
        } else {

            // get data by order todo ASC
            $getData = $this->database->getReference($this->tableName)
                ->orderByChild('todo')
                ->getValue();

            // get data by order  .value ASC
            // $getData = $this->database->getReference($this->tableName)
            //     ->orderByValue()
            //     ->getValue();

            // get data by order  isCompleted   ASC
            // $getData = $this->database->getReference($this->tableName)
            //     // ->orderByKey()
            //     ->orderByChild('isCompleted')
            //     ->equalTo(false)
            //     ->getValue();
        }


        $data = [];

        if ($getData) {

            foreach ($getData as $key => $row) {
                $data[] = [
                    "id" => $key,
                    "todo" => $row['todo'],
                    "isCompleted" => $row['isCompleted'],
                    "createdAt" => $row['createdAt'],
                ];
            }

            return response()->json(
                ['Response' => $data],
                200
            );
        } else {
            return response()->json(['Response' => "No Data"], 400);
        }
    }
    // add todo
    public function  add(Request $req)
    {
        if (isset($req->todo)) {

            // get data todo to compare duplicate  item
            $duplicate = false;
            $getData = $this->database->getReference($this->tableName)->getValue();
            foreach ($getData as $key => $row) {
                if ($row['todo'] == $req->todo) {
                    $duplicate = true;
                }
            }
            $postData = [
                'todo' => $req->todo,
                'isCompleted' => false,
                'createdAt' => date('d-m-Y h:m:i'),
            ];

            if ($duplicate == false) {

                $post = $this->database->getReference($this->tableName)->push($postData);

                if ($post) {
                    return response()->json(
                        ['Response' => "success"],
                        200
                    );
                } else {
                    return response()->json(['Response' => "unsuccess"], 400);
                }
            } else {
                return response()->json(['Response' => "Already Exists"], 400);
            }
        } else {
            return response()->json(['Response' => "Empty"], 400);
        }
    }

    // detail todo
    public function detail(Request $req)
    {
        $getData = $this->database->getReference($this->tableName)
            ->getChild($req->id)
            ->getValue();

        if ($getData) {
            return response()->json(
                ['Response' => $getData],
                200
            );
        } else {
            return response()->json(['Response' => "No Data"], 400);
        }
    }

    // update todo
    public function update(Request $req)
    {
        $id = $req->id;
        if (isset($req->todo)) {
            $duplicate = false;
            $getData = $this->database->getReference($this->tableName)->getValue();
            foreach ($getData as $key => $row) {
                if ($row['todo'] == $req->todo) {
                    $duplicate = true;
                }
            }
            $updateData = [
                'todo' => $req->todo,
            ];
            if ($duplicate == false) {
                $update = $this->database->getReference($this->tableName . '/' . $id)->update($updateData);

                if ($update) {
                    return response()->json(
                        ['Response' => "success"],
                        200
                    );
                } else {
                    return response()->json(['Response' => "not success"], 400);
                }
            } else {
                return response()->json(['Response' => "Already Exists"], 400);
            }
        } else {
            return response()->json(['Response' => "Empty"], 400);
        }
    }


    // delete todo
    public function delete(Request $req)
    {
        $id = $req->id;
        $update = $this->database->getReference($this->tableName . '/' . $id)->remove();
        if ($update) {
            return response()->json(
                ['Response' => "success"],
                200
            );
        } else {
            return response()->json(['Response' => "not success"], 400);
        }
    }

    // update complete

    public function todoComplete(Request $req)
    {

        $id = $req->id;
        if (isset($req->isCompleted)) {

            $isCompleted = (bool)true;

            if ($req->isCompleted == "false") {
                $isCompleted = (bool)false;
            }
            $updateData = [
                'isCompleted' => $isCompleted,
            ];

            $update = $this->database->getReference($this->tableName . '/' . $id)->update($updateData);

            if ($update) {
                return response()->json(
                    ['Response' => "success"],
                    200
                );
            } else {
                return response()->json(['Response' => "not success"], 400);
            }
        } else {
            return response()->json(['Response' => "Empty"], 400);
        }
    }
}
