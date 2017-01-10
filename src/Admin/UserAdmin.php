<?php

namespace MMC\FosUserBundle\Admin;

use FOS\UserBundle\Model\UserManagerInterface;
use MMC\SonataAdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class UserAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'mmc_sonata_admin_user';
    protected $baseRoutePattern = 'users';

    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    public function getExportFormats()
    {
        return [];
    }

    public function getBatchActions()
    {
        return [];
    }

    /**
     * @param UserManagerInterface $userManager
     */
    public function setUserManager(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('username')
            ->add('email')
            ->add('enabled')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('username', null, [
                'route' => [
                    'name' => 'show', ],
            ])
            ->add('email')
            ->add('enabled', null, ['editable' => true])
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('Detail')
                ->add('username')
                ->add('email')
                ->add('enabled')
                ->add('roles', 'choice', [
                    'choices' => array_flip($this->getRolesList()),
                    'catalogue' => 'UserAdmin',
                    'expanded' => false,
                    'multiple' => true,
                    'required' => false,
                ])
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('User')
                ->add('username')
                ->add('email')
                ->add('plainPassword', 'repeated', [
                    'type' => 'password',
                    'options' => ['translation_domain' => 'FOSUserBundle'],
                    'first_options' => ['label' => 'form.password'],
                    'validation_groups' => ['Default'],
                    'second_options' => ['label' => 'form.password_confirmation'],
                    'invalid_message' => 'fos_user.password.mismatch',
                    'required' => $this->getSubject()->getId() == null,
                ])
                ;

        if ($this->isGranted('MANAGE_ROLES')) {
            $formMapper
                ->add('roles', 'choice', [
                    'choices' => $this->getRolesList(),
                    'translation_domain' => 'UserAdmin',
                    'expanded' => false,
                    'multiple' => true,
                    'required' => false,
                ])
                ->add('enabled')
                ;
        }

        $formMapper
            ->end()
        ;
    }

    protected function getRolesList()
    {
        return [
            'Administrator' => 'ROLE_ADMIN',
            'User' => 'ROLE_USER',
            'Super Administrator' => 'ROLE_SUPER_ADMIN',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($user)
    {
        $this->userManager->updateCanonicalFields($user);
        $this->userManager->updatePassword($user);
    }
}
