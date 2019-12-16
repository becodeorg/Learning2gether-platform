<?php

namespace App\DataFixtures;

use App\Domain\LearningModuleType;
use App\Entity\Category;
use App\Entity\CategoryTranslation;
use App\Entity\Chapter;
use App\Entity\ChapterPage;
use App\Entity\ChapterPageTranslation;
use App\Entity\ChapterTranslation;
use App\Entity\Language;
use App\Entity\LearningModule;
use App\Entity\LearningModuleTranslation;
use App\Entity\Quiz;
use App\Entity\QuizAnswer;
use App\Entity\QuizAnswerTranslation;
use App\Entity\QuizQuestion;
use App\Entity\QuizQuestionTranslation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LearningModuleFixture extends Fixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 20;
    }

    public function load(ObjectManager $manager)
    {
        if ($_SERVER['APP_ENV'] !== 'dev') {
            return;
        }

        $allLanguages = $manager->getRepository(Language::class)->findAll();

        $learningModule = new LearningModule(
            '2ASjOU92SVejqTv1Mevaiw',
            '',
            LearningModuleType::SOFT(),
            true
        );

        $category = new Category();
        $category->setLearningModule($learningModule);

        foreach ($allLanguages AS $language) {
            $name = sprintf('Javascript: Introduction course (%s)', $language->getName());
            $learningModule->addTranslation(new LearningModuleTranslation(
                $learningModule,
                $language,
                $name,
                'Accusantium alias at autem blanditiis debitis dicta eaque enim ex excepturi fuga fugiat inventore ipsum iure
        molestias necessitatibus odit quia, quis quod similique.'
            ));
            $category->addTranslation(new CategoryTranslation($name, $category, $language));
        }

        $manager->persist($category);

        $chapters = [
            'Introduction to JavaScript' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque eaque fugit iure pariatur perferendis sint
    velit voluptates? Esse incidunt iure nemo nihil obcaecati praesentium quam reiciendis, repellat voluptas? Est,
    repellat. !!',
            'Variables and constants' => 'A deleniti dolorem ex hic iste iusto laudantium magnam nemo nisi officia, quo reprehenderit sed tempora temporibus,
    tenetur. Earum eos fugiat fugit harum hic natus non, praesentium saepe voluptatum? Sit!',
            'Functions and grouping behavior' => 'Autem consequatur doloribus error harum ipsa maxime nemo nihil nulla quis quo quod, quos reiciendis voluptatibus?
        Asperiores esse iusto libero odit quisquam quod soluta tenetur unde. Facilis minima odio tempora?',
            'Mastering the DOM' => 'Accusantium alias at autem blanditiis debitis dicta eaque enim ex excepturi fuga fugiat inventore ipsum iure
    molestias necessitatibus odit quia, quis quod similique sint soluta tempore totam, ullam veniam voluptas.',
        ];

        $pages = [
            'Introduction' => '# Big title
            
* This
* should
* be a
* bullet list
            
[A link to google](http://www.google.com?q=learning2gether)
            
!!{embed}(dQw4w9WgXcQ)
            
## Some example text
### Minor header 
Animi, commodi eveniet placeat quae quisquam repudiandae soluta ullam. Dignissimos excepturi facilis molestias mollitia nam neque nihil nulla, pariatur possimus provident quidem quis reiciendis repellat ut veniam, veritatis vero, voluptate!',
            'Let us dive into the theory!' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque eaque fugit iure pariatur perferendis sint
    velit voluptates? Esse incidunt iure nemo nihil obcaecati praesentium quam reiciendis, repellat voluptas? Est,
    repellat.',
            'Summary' => 'Autem consequatur doloribus error harum ipsa maxime nemo nihil nulla quis quo quod, quos reiciendis voluptatibus?
        Asperiores esse iusto libero odit quisquam quod soluta tenetur unde. Facilis minima odio tempora?',
        ];

        foreach ($chapters AS $chapterTitle => $chapterDescription) {
            $chapter = new Chapter($learningModule);

            foreach ($allLanguages AS $language) {
                $chapter->addTranslation(new ChapterTranslation(
                    $language,
                    $chapter,
                    $chapterTitle,
                    $chapterDescription
                ));
            }
            $learningModule->addChapter($chapter);

            foreach ($pages AS $pageTitle => $pageDescription) {
                $page = $chapter->createNewPage();
                foreach ($allLanguages AS $language) {
                    $page->addTranslation(new ChapterPageTranslation(
                        $language,
                        $page,
                        $pageTitle,
                        $pageDescription));
                }
                $chapter->addPage($page);
            }

            $chapter->setQuiz($quiz = new Quiz());

            foreach (range(1, 3) AS $questionNumber) {
                $question = new QuizQuestion($questionNumber, $quiz);
                foreach ($allLanguages AS $language) {
                    $question->addTranslation(new QuizQuestionTranslation(
                        $question, $language, 'Do you want to enter the correct answer to question ' . $questionNumber . '? (' . $language->getName() . ')'));
                }
                $question->addAnswer($answer = new QuizAnswer($question, true));
                foreach ($allLanguages AS $language) {
                    $answer->addTranslation(new QuizAnswerTranslation($answer, $language, 'Yes'));

                }
                $question->addAnswer($answer = new QuizAnswer($question, false));
                foreach ($allLanguages AS $language) {
                    $answer->addTranslation(new QuizAnswerTranslation($answer, $language, 'No'));

                }
                $quiz->addQuizQuestion($question);
            }
        }
        $manager->persist($learningModule);
        $manager->flush();
    }
}