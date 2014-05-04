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
use Application\Model\Application;
use Doctrine\Common\Collections\Criteria;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $searchForm = $this->initializeForm();

        $vacanciesRepository = $objectManager->getRepository('Application\Entity\Vacancy');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $application = new Application();
            $searchForm->setInputFilter($application->getInputFilter());
            $searchForm->setData($request->getPost());

            $criteria = Criteria::create();
            if ($searchForm->isValid()) {
                //TODO: move this to model
                $search = $searchForm->getData();
                if (!empty($search['division'])) {
                    $division = $objectManager->getRepository('Application\Entity\Division')->find($search['division']);
                    if (!empty($division)) {
                        $criteria->andWhere(Criteria::expr()->eq('division', $division));
                    }
                }
                if (!empty($search['language'])) {
                    $vacancyTexts = $objectManager->getRepository('Application\Entity\VacancyText')->matching(
                        Criteria::create()->where(Criteria::expr()->in('language', array($search['language'], 'en')))
                    );
                    $languageVacancies = $vacancyTexts->map(
                        function ($vacancyText) {
                            return $vacancyText->getVacancy()->getId();
                        }
                    );
                    $criteria->andWhere(
                        Criteria::expr()->in('id', $languageVacancies->toArray())
                    );
                }
                $vacancies = $vacanciesRepository->matching($criteria);
            }
        }

        $viewModel = new ViewModel();
        $viewModel->setVariable('vacancies', $vacancies);
        $viewModel->setVariable('searchForm', $searchForm);
        if (!empty($search)) {
            $viewModel->setVariable('search', $search);
        }

        return $viewModel;
    }

    /**
     * Prepares the form drop-down fields
     *
     * @return SearchForm $searchForm
     */
    private function initializeForm()
    {
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        //TODO: move this block to an appropriate place
        $query = $objectManager->createQuery('SELECT DISTINCT vt.language FROM Application\Entity\VacancyText vt');
        $dbLanguages = $query->getResult();
        $languages = array();
        foreach ($dbLanguages as $dbl) {
            $languages[$dbl['language']] = $dbl['language'];
        }

        //TODO: move this block to an appropriate place
        $query = $objectManager->createQuery('SELECT DISTINCT d.id, d.title FROM Application\Entity\Division d');
        $dbDivisions = $query->getResult();
        $divisions = array();
        foreach ($dbDivisions as $dbd) {
            $divisions[$dbd['id']] = $dbd['title'];
        }

        $searchForm = new SearchForm();
        $searchForm->get('language')->setAttributes(array('options' => $languages));
        $searchForm->get('division')->setAttributes(array('options' => $divisions));

        return $searchForm;
    }
}
