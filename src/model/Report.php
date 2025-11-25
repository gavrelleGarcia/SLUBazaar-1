<?php

declare(strict_types=1);


enum ReportType : string 
{
    case User = 'User';
    case Item = 'Item';
}


enum ReportStatus : string 
{
    case Pending = 'Pending';
    case InReview = 'In Review';
    case Resolved = 'Resolved';
    case Dismissed = 'Dismissed';
}

class Report 
{
    private int $reportId;
    private int $reporterId;
    private int $targetUserId;
    private int $targetItemId;
    private ReportType $reportType;
    private string $reasonType;
    private string $description;
    private ReportStatus $reportStatus;
    private string $adminNotes;
    private DateTimeImmutable $createdAt;

    public function __construct(int $reporterId, int $targetUserId, int $targetItemId, ReportType $reportType, string $reasonType, string $description, ReportStatus $reportStatus, string $adminNotes)
    {
        $this->reporterId = $reporterId;
        $this->targetUserId = $targetUserId;
        $this->targetItemId = $targetItemId;
        $this->reportType = $reportType;
        $this->reasonType = $reasonType;
        $this->description = $description;
        $this->reportStatus = $reportStatus;
        $this->adminNotes = $adminNotes;
        $this->createdAt = new DateTimeImmutable();
    }

    



    /**
     * Get the value of reportId
     */
    public function getReportId() : int
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
    public function getReporterId() : int
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
    public function getTargetUserId() : int
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
    public function getTargetItemId() : int
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
    public function getReportType() : ReportType
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
    public function getReasonType() : string
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
    public function getDescription() : string
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
    public function getReportStatus() : ReportStatus
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
    public function getAdminNotes() : string
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
    public function getCreatedAt() : DateTimeImmutable
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