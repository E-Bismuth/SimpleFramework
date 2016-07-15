<?php
/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 15/07/2016
 * Time: 10:03
 */


require_once('../../Core/autoload.php');

$model = new \exemples\Model\Test();
$model2 = new \exemples\Model\Test3();

//'SELECT * FROM tests'
//return array
$model->fields('id')->get();

//'SELECT tests.id FROM tests'
//return array
$model->fields('id')->get();

//'SELECT tests.id,tests.FirstName FROM tests'
//return array
$model->fields(['id','FirstName'])->get();

//'SELECT tests.id,tests.FirstName FROM tests LIMIT 0,1'
//return array
$model->fields(['id','FirstName'])->first();

//'SELECT tests.id,tests.FirstName FROM tests'
//return json
$model->fields(['id','FirstName'])->json();

//'SELECT tests.id,tests.FirstName FROM tests'
//return string
//!!will not execute the query
$model->fields(['id','FirstName'])->select()->debug();

//'SELECT tests.id,CONCAT(tests.FirstName, " ", tests.LastName) AS name FROM tests'
//return array
$model->fields(['id','name'=>['Action'=>'CONCAT','Value'=>['FirstName',' ','LastName']]])->get();

//'SELECT tests.id,SUM(tests.amount) AS Amount FROM tests'
//return array
$model->fields(['id','Amount'=>['Action'=>'SUM','Value'=>['amount']]])->get();



//'SELECT * FROM tests LIMIT 0,10', return array
$model->limit(0,10)->get();



//'SELECT * FROM tests ORDER BY tests.id ASC'
//return array
$model->orderBy('id')->get();

//'SELECT * FROM tests ORDER BY tests.id DESC'
//return array
$model->orderBy('!id')->get();

//'SELECT * FROM tests ORDER BY tests.FirstName ASC, tests.id DESC'
//return array
$model->orderBy(['FirstName','!id'])->get();



//'SELECT * FROM tests GROUP BY tests.id'
//return array
$model->groupBy('id')->get();

//'SELECT * FROM tests GROUP BY tests.FirstName, tests.id'
//return array
$model->groupBy(['FirstName','id'])->get();



//'SELECT * FROM tests WHERE (id=1)'
//return array
$model->where('id = 1')->get();

//'SELECT * FROM tests WHERE (id=1) AND (FirstName = "test")'
//return array
$model->where(['id = 1','FirstName = "test"'])->get();

//'SELECT * FROM tests WHERE (id=1) AND (FirstName = "test" OR LastName = "test")'
//return array
$model->where(['id = 1','FirstName = "test" OR LastName = "test"'])->get();


//'SELECT * FROM tests WHERE (id = :id)'
//  ||  execute(array('id'=>1))
//return array
$model->where('id = :id')->inject(['id'=>1])->get();

//'SELECT * FROM tests WHERE (id = :id) AND (FirstName = :getName)'
//  ||  execute(array('id'=>1,'getName'=>"test"))
//return array
$model->where(['id = :id','FirstName = :getName'])->inject(['id'=>1,'getName'=>'test'])->get();

//'SELECT tests.id,test2.TestField
// FROM tests
// LEFT JOIN test2 ON test2.id = tests.test2_id
// WHERE (test2.id = :id)'
//  ||  execute(array('id'=>5))
//return array
$model->fields('id')->Test2('left',function(\exemples\Model\Test2 $e){
    return $e->fields('TestField')->where('id = :id')->inject(['id'=>5]);
})->get();



//'INSERT INTO tests (id,FirstName)
// VALUES (:id,:FirstName)'
//  ||  execute(array('id'=>1,'FirstName'=>"test"))
$model->fields(['id','FirstName'])->inject(['id'=>1,'FirstName'=>'test'])->insert()->exec();

//!!will not execute the query
//return String
$model->fields(['id','FirstName'])->inject(['id'=>1,'FirstName'=>'test'])->insert()->debug();


//'UPDATE tests
// SET FirstName = :FirstName , LastName = :LastName
//WHERE id = :id'
//  ||  execute(array('id'=>1,'FirstName'=>"test",'LastName'=>"test"))
$model->fields(['FirstName','LastName'])->where('id = :id')->inject(['id'=>1,'FirstName'=>'test','LastName'=>'test'])->update()->exec();

//'UPDATE tests
// SET FirstName = :FirstName , LastName = :LastName'
//  ||  execute(array('FirstName'=>"test",'LastName'=>"test"))
// !!!! Throw ModelException !!!! can't update without where on this table $needWhereOnUpdate is set as true (Look exemple after)
$model->fields(['FirstName','LastName'])->inject(['FirstName'=>'test','LastName'=>'test'])->update()->exec();

//'UPDATE tests
// SET FirstName = :FirstName , LastName = :LastName'
//  ||  execute(array('FirstName'=>"test",'LastName'=>"test"))
// Will update all the table because on this Model Class the $needWhereOnUpdate is set as false
$model2->fields(['FirstName','LastName'])->inject(['FirstName'=>'test','LastName'=>'test'])->update()->exec();


//'DELETE tests
//WHERE id = :id'
//  ||  execute(array('id'=>1))
$model->where('id = :id')->inject(['id'=>1])->delete()->exec();

//'DELETE tests'
// !!!! Throw ModelException !!!! can't update without where on this table $needWhereOnDelete is set as true (Look exemple after)
$model->inject(['FirstName'=>'test','LastName'=>'test'])->delete()->exec();

//'DELETE tests'
// Will delete all the table because on this Model Class the $needWhereOnDelete is set as false
$model2->inject(['FirstName'=>'test','LastName'=>'test'])->delete()->exec();