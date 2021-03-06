<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Organisation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;

class OrganisationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {    $orgs=Organisation::where('id','<>','1')->get();
        return view('admin.Organisation.organisation_index',compact('orgs'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.Organisation.create_organisation');
      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {  
        
        $org=Organisation::create($request->except(['_token']));
        $dbName=$org->name.'_'.$org->id;
        /*\Illuminate\Support\Facades\Config::set('database.connections.'.$dbName, array(
            'driver'    => 'mysql',
            'host'      => '127.0.0.1',
            'database'  => $dbName,
            'username'  => 'root',
            'password'  => '',  
        ));*/
        #DB::setDefaultConnection($dbName); 
       try{
        DB::statement("create database ".$dbName);
        }
        catch(QueryException  $e){
            $this->destroy($org->id);
            return redirect()->route('organisation.index')->withMessage('Oganisation Not Created');
 
        }
        \Illuminate\Support\Facades\Config::set('database.connections.'.$dbName, array(
            'driver'    => 'mysql',
            'host'      => '127.0.0.1',
            'database'  => $dbName,
            'username'  => 'root',
            'password'  => '',  
        ));       

        Schema::connection($dbName)->create('modules', function($table)
        {
            $table->increments('id');
            $table->string('name');
       });
       Schema::connection($dbName)->create('surveys', function($table)
       {
            $table->increments('id');
            $table->string('name');
            $table->text('json');
            $table->integer('creator_id')->unsigned();
            $table->timestamps();
      });
      Schema::connection($dbName)->create('survey_results', function($table)
      {
            $table->increments('id');
            $table->unsignedInteger('survey_id');
            $table->unsignedInteger('user_id')->nullable();
            $table->text('json');
            $table->timestamps();
     });
       
      
       
        return redirect()->route('organisation.index')->withMessage('Oganisation Created');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $org=Organisation::find($id);
       return view('admin.Organisation.edit',compact('org'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $org=Organisation::find($id);
        $org->name=$request->name;
        $org->service=$request->service;
       
        $org->save();
        return redirect()->route('organisation.index')->withMessage('Oganisation Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   $org=Organisation::find($id);
        DB::table('roles')->where('org_id',$id)->delete();
        DB::table('organisations')->where('id',$id)->delete();
        return redirect()->route('organisation.index')->withMessage('Oganisation Deleted');
    }
}
