<?php

namespace App\DataFixtures;

use App\Domain\LearningModuleType;
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

        /** @var Language $english */
        $english = $manager->getRepository(Language::class)
            ->findOneBy(['code' => 'en']);

        $learningModule = new LearningModule(
            '',
            '',
            LearningModuleType::SOFT(),
            true
        );
        $learningModule->addTranslation(new LearningModuleTranslation(
            $learningModule,
            $english,
            'Javascript: Introduction course',
            'Accusantium alias at autem blanditiis debitis dicta eaque enim ex excepturi fuga fugiat inventore ipsum iure
    molestias necessitatibus odit quia, quis quod similique.'
        ));

        $chapters = [
            'Introduction to JavaScript' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque eaque fugit iure pariatur perferendis sint
    velit voluptates? Esse incidunt iure nemo nihil obcaecati praesentium quam reiciendis, repellat voluptas? Est,
    repellat. !!{embed}(dQw4w9WgXcQ)',
            'Variables and constants' => 'A deleniti dolorem ex hic iste iusto laudantium magnam nemo nisi officia, quo reprehenderit sed tempora temporibus,
    tenetur. Earum eos fugiat fugit harum hic natus non, praesentium saepe voluptatum? Sit!',
            'Functions and grouping behavior' => 'Autem consequatur doloribus error harum ipsa maxime nemo nihil nulla quis quo quod, quos reiciendis voluptatibus?
        Asperiores esse iusto libero odit quisquam quod soluta tenetur unde. Facilis minima odio tempora?',
            'Mastering the DOM' => 'Accusantium alias at autem blanditiis debitis dicta eaque enim ex excepturi fuga fugiat inventore ipsum iure
    molestias necessitatibus odit quia, quis quod similique sint soluta tempore totam, ullam veniam voluptas.',
        ];

        $pages = [
            'Introduction' => '#Big title
            
            * This
            * should
            * be a
            * bullet list
            
            [A link to google](http://www.google.com?q=learning2gether)
            
            !!{embed}(dQw4w9WgXcQ)
            
            ## Some example text
            
            Animi, commodi eveniet placeat quae quisquam repudiandae soluta ullam. Dignissimos excepturi facilis molestias
    mollitia nam neque nihil nulla, pariatur possimus provident quidem quis reiciendis repellat ut veniam, veritatis
    vero, voluptate!',
            'Let us dive into the theory!' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque eaque fugit iure pariatur perferendis sint
    velit voluptates? Esse incidunt iure nemo nihil obcaecati praesentium quam reiciendis, repellat voluptas? Est,
    repellat.',
            'Summary' => 'Autem consequatur doloribus error harum ipsa maxime nemo nihil nulla quis quo quod, quos reiciendis voluptatibus?
        Asperiores esse iusto libero odit quisquam quod soluta tenetur unde. Facilis minima odio tempora?',
        ];

        foreach ($chapters AS $chapterTitle => $chapterDescription) {
            $chapter = new Chapter($learningModule);

            $chapter->addTranslation(new ChapterTranslation(
                $english,
                $chapter,
                $chapterTitle,
                $chapterDescription
            ));
            $learningModule->addChapter($chapter);

            foreach ($pages AS $pageTitle => $pageDescription) {
                $page = $chapter->createNewPage();
                $page->addTranslation(new ChapterPageTranslation(
                    $english,
                    $page,
                    $pageTitle,
                    $pageDescription));
                $chapter->addPage($page);
            }

            $chapter->setQuiz($quiz = new Quiz());

            foreach (range(1, 3) AS $questionNumber) {
                $question = new QuizQuestion(1, $quiz);
                $question->addTranslation(new QuizQuestionTranslation(
                    $question, $english, 'Do you want to enter the correct answer to question ' . $questionNumber . '?'));

                $question->addAnswer($answer = new QuizAnswer($question, true));
                $answer->addTranslation(new QuizAnswerTranslation($answer, $english, 'Yes'));

                $question->addAnswer($answer = new QuizAnswer($question, false));
                $answer->addTranslation(new QuizAnswerTranslation($answer, $english, 'No'));

                $quiz->addQuizQuestion($question);
            }
        }

        $manager->persist($learningModule);
        $manager->flush();
    }
}