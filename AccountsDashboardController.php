<?php

use Carbon\Carbon;
class AccountsDashboardController extends BaseController {

use fetchUserDetailsTrait;

	/**
	 * [before description]
	 * @return [type] [description]
	 */
	protected $user;

	public function before()
	{

		$this->user = $this->user();

		// Checks if the user is clocked out.
		if(!$this->user->in_out)
			$this->view->redirect('/');

        if($this->user->accounts_access != 'Accounts')
            $this->view->redirect('/dashboard');

	}


	/**
	 * [getIndex description]
	 * @return [type] [description]
	 */
	public function getIndex()
	{
		/*if(is_null($this->user->role) || $this->user->role != 1)
			$this->view->redirect('/dashboard');*/

		$this->view->setPage('accounts/index');


		$this->view->render(array(
            "title" => "MCE | Accounts Portal",
		));
	}



}
