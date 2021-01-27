<?php 

namespace App\Controllers;

use App\Entities\Task;
use \App\Models\TaskModel;
use \CodeIgniter\Exceptions\PageNotFoundException;

class Tasks extends BaseController
{
	private $model;

	public function __construct()
	{
		$this->model = new TaskModel;
	}

	public function index()
	{
		$data = $this->model->findAll();

		return view("Tasks/index", [
			'tasks' => $data]);
	}

	public function show($id)
	{
		$task = $this->getTaskOr404($id);

		return view('Tasks/show', [
			'task' => $task
		]);
	}

	public function new()
	{
		$task = new Task;

		return view('Tasks/new', [
			'task' => $task
		]);
	}

	public function create()
	{
		// This will fill the Task with allowed fields as defined in model
		$task = new Task($this->request->getPost());

		if ($this->model->insert($task)) {
			return redirect()->to("/tasks/show/{$this->model->insertID}")
							 ->with('info', 'Task created successfully');

			
		} else {
			return redirect()->back()
							 ->with('errors', $this->model->errors())
							 ->with('warning', 'Invalid data')
							 ->withInput();
		}
	}

	public function edit($id)
	{
		$task = $this->getTaskOr404($id);

		return view('Tasks/edit', [
			'task' => $task
		]);
	}

	public function update($id) 
	{
		$task = $this->getTaskOr404($id);

		$task->fill($this->request->getPost());

		if(!$task->hasChanged()) {
			return redirect()->back()
							 ->with('warning', 'Nothing to Update')
							 ->withInput();
		}

		if ($this->model->save($task)) {
			return redirect()->to("/tasks/show/$id")
						 	 ->with('info', 'Task updated successfully');
		} else {
			return redirect()->back()
							 ->with('errors', $this->model->errors())
							 ->with('warning', 'Invalid data')
							 ->withInput();
		}
	}

	private function getTaskOr404($id) 
	{
		$task = $this->model->find($id);

		if ($task === null) {
			throw new PageNotFoundException("Task with id $id not found");
		}

		return $task;
	}
}
