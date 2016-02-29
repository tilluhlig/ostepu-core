<?php
/**
 * @file TutorAssignment.php contains the TutorAssignment class
 *
 * @author Till Uhlig <till.uhlig@student.uni-halle.de>
 * @date 2013-2015
 */

include_once ( dirname( __FILE__ ) . '/Object.php' );

/**
 * the tutor assignment structure
 */
class TutorAssignment extends Object implements JsonSerializable
{

    /**
     * @var User $turor the tutor
     */
    private $tutor = null;

    /**
     * the $tutor getter
     *
     * @return the value of $tutor
     */
    public function getTutor( )
    {
        return $this->tutor;
    }

    /**
     * the $tutor setter
     *
     * @param User $value the new value for $tutor
     */
    public function setTutor( $value = null )
    {
        $this->tutor = $value;
    }

    /**
     * @var string[] $id ids of the submissions the tutor was assigned to correct.
     */
    private $submissionIds = array( );

    /**
     * the $submissionIds getter
     *
     * @return the value of $submissionIds
     */
    public function getSubmissionIds( )
    {
        return $submissionIds;
    }

    /**
     * the $submissionIds setter
     *
     * @param string[] $value the new value for $submissionIds
     */
    public function setSubmissionIds( $value = array( ) )
    {
        $submissionIds = $value;
    }

    /**
     * the json serialize function
     */
    public function jsonSerialize( )
    {
        return array(
                     'tutor' => $this->tutor,
                     'submissionIds' => $submissionIds
                     );
    }
}

 