<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CategoriesTest extends TestCase
{
    /**
    * Test: creating root node
    *
    * @return void
    */
    public function testCreateNode()
    {
        $this->post('/node')
             ->seeJson([
                 'result' => 'OK',
             ]);
    }

    /**
    * Test: updating root node
    *
    * @return void
    */
    public function testUpdateNode()
    {
        $node = \App\Category::where('parent_id', null)->first();
        $this->put('/node/'.$node->id, ['name' => 'Testing node'])
             ->seeJson([
                 'result' => 'OK',
             ]);
    }

    /**
    * Test: creating child node
    *
    * @return void
    */
    public function testCreateChild()
    {
        $node = \App\Category::where('parent_id', null)->first();
        $this->post('/node/'.$node->id.'/children')
             ->seeJson([
                 'result' => 'OK',
             ]);
    }

    /**
    * Test: updating child node
    *
    * @return void
    */
    public function testUpdateChild()
    {
        $node = \App\Category::where('parent_id', null)->first();
        $this->put('/node/'.$node->id.'/children/1', ['name' => 'Testing child node'])
             ->seeJson([
                 'result' => 'OK',
             ]);
    }

    /**
    * Test: render root node
    *
    * @return void
    */
    public function testShowNode()
    {
        $node = \App\Category::where('parent_id', null)->first();
        $this->get('/node/'.$node->id)
             ->dontSee('Node not found!');
    }

    /**
    * Test: render node children
    *
    * @return void
    */
    public function testShowChildren()
    {
        $node = \App\Category::where('parent_id', null)->first();
        $this->get('/node/'.$node->id.'/children')
             ->dontSee('Node not found!');
    }

    /**
    * Test: render child node
    *
    * @return void
    */
    public function testShowChild()
    {
        $node = \App\Category::where('parent_id', null)->first();
        $this->get('/node/'.$node->id.'/children/1')
             ->dontSee('Node not found!');
    }

    /**
    * Test: destroying child node
    *
    * @return void
    */
    public function testDestroyChild()
    {
        $node = \App\Category::where('parent_id', null)->first();
        $this->delete('/node/'.$node->id.'/children/1')
             ->seeJson([
                 'result' => 'OK',
             ]);
    }

    /**
    * Test: destroying root node
    *
    * @return void
    */
    public function testDestroyNode()
    {
        $node = \App\Category::where('parent_id', null)->first();
        $this->delete('/node/'.$node->id)
             ->seeJson([
                 'result' => 'OK',
             ]);
    }
}
