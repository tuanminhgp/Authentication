<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CommentFixture extends BaseFixture implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Comment::class,100,function (Comment $comment){

            $comment->setContent(
            $this->faker->boolean ? $this->faker->paragraph :$this->faker->sentences(2,true));

            $comment->setAuthorName($this->faker->name);
            $comment->setCreatedAt($this->faker->dateTimeBetween('-1 months','-1 days'));
            $comment->setIsDeleted($this->faker->boolean(20));
            $comment->setArticle($this->getReference(Article::class.'_'.$this->faker->numberBetween(0,9)));

        });
        $manager->flush();
    }


    public function getDependencies()
    {
    return [
        ArticleFixtures::class

    ];
}

}
