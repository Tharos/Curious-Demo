<?php

use Model\Entity\User;
use Model\Repository\UserRepository;
use Nette\Forms\Form;

/**
 * @author Vojtěch Kohout
 */
class UserForm
{

	/** @var Redirector */
	private $redirector;

	/** @var UserRepository */
	private $userRepository;


	/**
	 * @param Redirector $redirector
	 * @param UserRepository $userRepository
	 */
	public function __construct(Redirector $redirector, UserRepository $userRepository)
	{
		$this->redirector = $redirector;
		$this->userRepository = $userRepository;
	}

	/**
	 * @return Form
	 */
	public function createCreateForm()
	{
		$form = $this->createFormBase();
		$form->addSubmit('submit', 'Vytvořit uživatele');

		return $form;
	}

	/**
	 * @param User $user
	 * @return Form
	 */
	public function createUpdateForm(User $user)
	{
		$form = $this->createFormBase();
		$form->addSubmit('submit', 'Upravit uživatele');

		$form->setDefaults($user->getData());

		return $form;
	}

	/**
	 * @param Form $form
	 * @param string $successRedirect
	 * @param User $user
	 */
	public function processForm(Form $form, $successRedirect, User $user = null)
	{
		$user or $user = new User;
		$user->assign($form->getValues(), array('name', 'email', 'note'));
		if ($user->note === '') {
			$user->note = null;
		}
		try {
			$this->userRepository->persist($user);
			$this->redirector->redirect($successRedirect);
		} catch (\Exception $e) {
			$form->addError($e->getMessage());
		}
	}

	////////////////////
	////////////////////

	/**
	 * @return Form
	 */
	private function createFormBase()
	{
		$form = new Form;

		$form->addText('name', 'Jméno:')->setRequired('Vyplňte prosím jméno');
		$form->addText('email', 'E-mail:')->addRule(Form::EMAIL, 'Vyplňte prosím platnou e-mailovou adresu');
		$form->addText('note', 'Poznámka:');

		return $form;
	}

}
