<?php

use Curious\Exception\BadRequestException;
use Curious\Fragment;
use Curious\Linker;
use Curious\State;
use Model\Query\UserQuery;
use Model\Repository\UserRepository;
use Nette\Templating\FileTemplate;

/**
 * @fragment initUsers
 * @following initNavigation
 * @usingState keyword
 */
function initUsers(Navigation $navigation, FileTemplate $template, Directories $directories, Fragment $fragment)
{
	$navigation->setActiveFragment('users');
	$template->setFile(
		$directories->getTemplatesDirectory() . '/users/' . $fragment->getName() . '.latte'
	);
}

/**
 * @fragment users
 * @following initUsers
 */
function listUsers(FileTemplate $template, UserRepository $userRepository, UserFilterForm $userFilterForm, Redirector $redirector, Linker $linker, State $state)
{
	$template->filter = $filter = $userFilterForm->createCreateForm();
	if ($filter->isSuccess()) {
		$keyword = $filter['keyword']->getValue();
		$redirector->redirect($linker->createLink('this', array('keyword' => ($keyword !== '' ? $keyword : null))));
	}
	$filter['keyword']->setDefaultValue(
		$keyword = $state->get('keyword', null)
	);
	$query = new UserQuery;
	$template->users = $userRepository->findBy($query->filterByKeyword($keyword));
}

/**
 * @fragment user-create
 * @following initUsers
 */
function createUser(FileTemplate $template, UserForm $userForm, Linker $linker)
{
	$template->form = $form = $userForm->createCreateForm();
	if ($form->isSuccess()) {
		$userForm->processForm($form, $linker->createLink('users'));
	}
}

/**
 * @fragment initConcreteUser
 * @following initUsers
 */
function initConcreteUser(FileTemplate $template, UserRepository $userRepository, State $state, Data $data)
{
	if (!isset($state->id)) {
		throw new BadRequestException;
	}
	$data->user = $template->user = $userRepository->find($state->id);
}

/**
 * @fragment user-update
 * @following initConcreteUser
 */
function updateUser(FileTemplate $template, UserForm $userForm, Linker $linker, Data $data)
{
	$user = $data->user;
	$template->form = $form = $userForm->createUpdateForm($user);
	if ($form->isSuccess()) {
		$userForm->processForm($form, $linker->createLink('users'), $user);
	}
}

/**
 * @fragment user-delete
 * @following initConcreteUser
 */
function deleteUser(FileTemplate $template, ConfirmForm $confirmForm, Linker $linker, Data $data, Redirector $redirector, UserRepository $userRepository)
{
	$form = $confirmForm->createCreateForm();
	if ($form->isSuccess()) {
		if ($form['ok']->isSubmittedBy()) {
			$userRepository->delete($data->user);
		}
		$redirector->redirect($linker->createLink('users'));
	}
	$template->form = $form;
}