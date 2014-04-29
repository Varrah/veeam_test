<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Form\SearchForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $searchForm = $this->initializeForm();

        $request = $this->getRequest();
        if ( $request->isPost() ) {
            $searchForm->setData( $request->getPost() );

            if ( $searchForm->isValid() ) {
                //TODO: move this to model
                $search = $searchForm->getData();
                $dql = <<<DQL
                    SELECT v
                    FROM Application\Entity\Vacancy v
                    JOIN v.vacancyTexts vt
                    JOIN v.division d
                    WHERE
                        d.id = {$search['division']} AND
                        vt.language IN( 'en', '{$search['language']}')
DQL;

                $query = $objectManager->createQuery($dql);
                //var_dump( $query );
                $vacancies = $query->getResult();
            } else {
                $vacancies = $objectManager->getRepository( 'Application\Entity\Vacancy' )->findAll();
            }
        } else {
            $vacancies = $objectManager->getRepository( 'Application\Entity\Vacancy' )->findAll();
        }

        $viewModel = new ViewModel();
        $viewModel->setVariable('vacancies', $vacancies);
        $viewModel->setVariable('searchForm', $searchForm);

        return $viewModel;
    }

    /**
     * Prepares the form drop-down fields
     * @return SearchForm $searchForm
     */
    private function initializeForm() {
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        //TODO: move this block to an appropriate place
        $query = $objectManager->createQuery('SELECT DISTINCT vt.language FROM Application\Entity\VacancyText vt');
        $dbLanguages = $query->getResult();
        $languages = array();
        foreach ( $dbLanguages as $dbl ) {
            $languages[$dbl['language']] = $dbl['language'];
        }

        //TODO: move this block to an appropriate place
        $query = $objectManager->createQuery('SELECT DISTINCT d.id, d.title FROM Application\Entity\Division d');
        $dbDivisions = $query->getResult();
        $divisions = array();
        foreach ( $dbDivisions as $dbd ) {
            $divisions[$dbd['id']] = $dbd['title'];
        }

        $searchForm = new SearchForm();
        $searchForm->get('language')->setAttributes( array( 'options' => $languages ) );
        $searchForm->get('division')->setAttributes( array( 'options' => $divisions ) );

        return $searchForm;
    }
}
