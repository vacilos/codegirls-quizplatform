<?php

namespace Vacilos\QuizBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="quizquestion")
 * @ORM\HasLifecycleCallbacks
 */
class QuizQuestion {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Quiz")
     * @ORM\JoinColumn(name="quiz_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $quiz;

    /**
     * @ORM\ManyToOne(targetEntity="Question")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $question;

    /**
     * @var string $ordering
     *
     * @ORM\Column(name="ordering", type="smallint")
     */
    private $ordering;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ordering
     *
     * @param integer $ordering
     *
     * @return QuizQuestion
     */
    public function setOrdering($ordering)
    {
        $this->ordering = $ordering;

        return $this;
    }

    /**
     * Get ordering
     *
     * @return integer
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return QuizQuestion
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
     * @return QuizQuestion
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
     * Set quiz
     *
     * @param \Vacilos\QuizBundle\Entity\Quiz $quiz
     *
     * @return QuizQuestion
     */
    public function setQuiz(\Vacilos\QuizBundle\Entity\Quiz $quiz = null)
    {
        $this->quiz = $quiz;

        return $this;
    }

    /**
     * Get quiz
     *
     * @return \Vacilos\QuizBundle\Entity\Quiz
     */
    public function getQuiz()
    {
        return $this->quiz;
    }

    /**
     * Set question
     *
     * @param \Vacilos\QuizBundle\Entity\Question $question
     *
     * @return QuizQuestion
     */
    public function setQuestion(\Vacilos\QuizBundle\Entity\Question $question = null)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return \Vacilos\QuizBundle\Entity\Question
     */
    public function getQuestion()
    {
        return $this->question;
    }
}
