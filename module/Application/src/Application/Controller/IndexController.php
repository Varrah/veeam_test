<?php

namespace Application\Controller;

use Application\Form\SearchForm;
use Application\Model\Application;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $search = array('language' => '', 'division' => '');
        /** @var \Doctrine\ORM\EntityManager $objectManager */
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        //TODO: Consider proper caching
        $searchForm = $this->initializeForm($objectManager);

        //Check if we got any filters set
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $application = new Application();
            $searchForm->setInputFilter($application->getInputFilter());
            $searchForm->setData($request->getPost());

            if ($searchForm->isValid()) {
                $search = $searchForm->getData();
            }
        }

        //Get vacancies according to filters
        $vacancies = $this->getVacancies($search, $objectManager);

        $viewModel = new ViewModel();
        $viewModel->setVariable('vacancies', $vacancies);
        $viewModel->setVariable('searchForm', $searchForm);
        if (!empty($search)) {
            $viewModel->setVariable('search', $search);
        }

        return $viewModel;
    }

    /**
     * Prepares the form drop-down fields etc.
     * @param \Doctrine\ORM\EntityManager $objectManager
     * @return SearchForm $searchForm
     */
    private function initializeForm($objectManager)
    {
        $searchForm = new SearchForm();
        $searchForm->get('language')->setAttributes(array('options' => $this->getAvailableLanguages($objectManager)));
        $searchForm->get('division')->setAttributes(array('options' => $this->getAvailableDivisions($objectManager)));

        return $searchForm;
    }

    /**
     * @param \Doctrine\ORM\EntityManager $objectManager
     * @return array $languages suitable to use in form select render
     */
    private function getAvailableLanguages($objectManager)
    {
        $dbLanguages = $objectManager
            ->createQuery('SELECT DISTINCT vt.language FROM Application\Entity\VacancyText vt')
            ->getResult();

        //Could use a mapping function here, but simple foreach will suit
        $languages = array();
        foreach ($dbLanguages as $dbl) {
            $languages[$dbl['language']] = $dbl['language'];
        }
        return $languages;
    }

    /**
     * @param \Doctrine\ORM\EntityManager $objectManager
     * @return array $divisions suitable to use in form select render
     */
    private function getAvailableDivisions($objectManager)
    {
        $dbDivisions = $objectManager
            ->createQuery('SELECT DISTINCT d.id, d.title FROM Application\Entity\Division d')
            ->getResult();
        $divisions = array();
        foreach ($dbDivisions as $dbd) {
            $divisions[$dbd['id']] = $dbd['title'];
        }

        return $divisions;
    }

    /**
     * @param Array                       $search
     * @param \Doctrine\ORM\EntityManager $objectManager
     *
     * @return ArrayCollection $vacancies
     */
    private function getVacancies($search, $objectManager)
    {
        $vacanciesRepository = $objectManager->getRepository('Application\Entity\Vacancy');
        if (empty($search['language']) && empty($search['division'])) {
            //Got no search? Print all vacancies out
            return $vacanciesRepository->findAll();
        }

        //This way of DB filtering uses too many requests, yet looks far better then combining the query by hand
        $criteria = Criteria::create();

        if (!empty($search['division'])) {
            $division = $objectManager
                ->getRepository('Application\Entity\Division')
                ->find($search['division']);

            if (!empty($division)) {
                $criteria->andWhere(Criteria::expr()->eq('division', $division));
            }
        }

        if (!empty($search['language'])) {
            //Find vacancyTexts of selected language, or English
            $vacancyTexts = $objectManager->getRepository('Application\Entity\VacancyText')->matching(
                Criteria::create()->where(Criteria::expr()->in('language', array($search['language'], 'en')))
            );

            $languageVacancies = $vacancyTexts->map(
                function ($vacancyText) {
                    /** @var \Application\Entity\VacancyText $vacancyText */
                    return $vacancyText->getVacancy()->getId();
                }
            );

            $criteria->andWhere(
                Criteria::expr()->in('id', $languageVacancies->toArray())
            );
        }

        return $vacanciesRepository->matching($criteria);
    }
}
