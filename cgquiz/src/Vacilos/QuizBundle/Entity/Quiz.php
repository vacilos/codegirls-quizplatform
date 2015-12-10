<?php

namespace Vacilos\QuizBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="quiz")
 * @ORM\HasLifecycleCallbacks
 */
class Quiz {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=500)
     */
    private $title;

    /**
     * @var string $title
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(name="created", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     */
    private $created;

    /**
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    private $updated;

    /**
     * @ORM\OneToMany(targetEntity="QuizQuestion", mappedBy="question")
     */
    private $quizQuestions;

    public function __toString() {
        return $this->title;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Quiz
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Quiz
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Quiz
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Quiz
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Quiz
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->quizQuestions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add quizQuestion
     *
     * @param \Vacilos\QuizBundle\Entity\QuizQuestion $quizQuestion
     *
     * @return Quiz
     */
    public function addQuizQuestion(\Vacilos\QuizBundle\Entity\QuizQuestion $quizQuestion)
    {
        $this->quizQuestions[] = $quizQuestion;

        return $this;
    }

    /**
     * Remove quizQuestion
     *
     * @param \Vacilos\QuizBundle\Entity\QuizQuestion $quizQuestion
     */
    public function removeQuizQuestion(\Vacilos\QuizBundle\Entity\QuizQuestion $quizQuestion)
    {
        $this->quizQuestions->removeElement($quizQuestion);
    }

    /**
     * Get quizQuestions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuizQuestions()
    {
        return $this->quizQuestions;
    }
}
