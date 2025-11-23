<?php

declare(strict_types=1);

class Report 
{
    protected $reportId;
    protected $reporterId;
    protected $targetUserId;
    protected $targetItemId;
    protected $reportType;
    protected $reasonType;
    protected $description;
    protected $reportStatus;
    protected $adminNotes;
    protected $createdAt;

    public function __construct($reportId, $reporterId, $targetUserId, $targetItemId, $reportType, $reasonType, $description, $reportStatus, $adminNotes, $createdAt)
    {
        $this->reportId = $reportId;
        $this->reporterId = $reporterId;
        $this->targetUserId = $targetUserId;
        $this->targetItemId = $targetItemId;
        $this->reportType = $reportType;
        $this->reasonType = $reasonType;
        $this->description = $description;
        $this->reportStatus = $reportStatus;
        $this->adminNotes = $adminNotes;
        $this->createdAt = $createdAt;
    }

    



    /**
     * Get the value of reportId
     */
    public function getReportId()
    {
        return $this->reportId;
    }

    /**
     * Set the value of reportId
     */
    public function setReportId($reportId): self
    {
        $this->reportId = $reportId;

        return $this;
    }

    /**
     * Get the value of reporterId
     */
    public function getReporterId()
    {
        return $this->reporterId;
    }

    /**
     * Set the value of reporterId
     */
    public function setReporterId($reporterId): self
    {
        $this->reporterId = $reporterId;

        return $this;
    }

    /**
     * Get the value of targetUserId
     */
    public function getTargetUserId()
    {
        return $this->targetUserId;
    }

    /**
     * Set the value of targetUserId
     */
    public function setTargetUserId($targetUserId): self
    {
        $this->targetUserId = $targetUserId;

        return $this;
    }

    /**
     * Get the value of targetItemId
     */
    public function getTargetItemId()
    {
        return $this->targetItemId;
    }

    /**
     * Set the value of targetItemId
     */
    public function setTargetItemId($targetItemId): self
    {
        $this->targetItemId = $targetItemId;

        return $this;
    }

    /**
     * Get the value of reportType
     */
    public function getReportType()
    {
        return $this->reportType;
    }

    /**
     * Set the value of reportType
     */
    public function setReportType($reportType): self
    {
        $this->reportType = $reportType;

        return $this;
    }

    /**
     * Get the value of reasonType
     */
    public function getReasonType()
    {
        return $this->reasonType;
    }

    /**
     * Set the value of reasonType
     */
    public function setReasonType($reasonType): self
    {
        $this->reasonType = $reasonType;

        return $this;
    }

    /**
     * Get the value of description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     */
    public function setDescription($description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of reportStatus
     */
    public function getReportStatus()
    {
        return $this->reportStatus;
    }

    /**
     * Set the value of reportStatus
     */
    public function setReportStatus($reportStatus): self
    {
        $this->reportStatus = $reportStatus;

        return $this;
    }

    /**
     * Get the value of adminNotes
     */
    public function getAdminNotes()
    {
        return $this->adminNotes;
    }

    /**
     * Set the value of adminNotes
     */
    public function setAdminNotes($adminNotes): self
    {
        $this->adminNotes = $adminNotes;

        return $this;
    }

    /**
     * Get the value of createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     */
    public function setCreatedAt($createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}