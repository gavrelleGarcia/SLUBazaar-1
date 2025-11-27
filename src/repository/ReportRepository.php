<?php

declare(strict_types=1);

class ReportRepository 
{
    private mysqli $db;


    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function addReport(Report $report)
    {
        $query = "INSERT INTO report(reporter_id, target_user_id, target_item_id, 
                                report_type, reason_type, description, created_at) 
                    values (?,?,?,?,?,?,?)";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was an error preparing the addReport query : " . $this->db->error);

        $reporterId = $report->getReporterId();
        $targetUserId = $report->getTargetUserId();
        $targetItemId = $report->getTargetItemId();
        $reportType = $report->getReportType();
        $reasonType = $report->getReasonType();
        $description = $report->getDescription();
        $createdAt = $report->getCreatedAt();
        $statement->bind_param('iiissss', $reporterId, $targetUserId, $targetItemId, 
                                        $reportType, $reasonType, $description, $createdAt);

        if (!$statement->execute())
            throw new Exception("Failed to add report : " . $statement->error);
        
        $report->setReportId($this->db->insert_id);
        $statement->close();
    }



    public function updateReport(int $reportId, $newStatus) : void
    {
        $query = "UPDATE report SET report_status = ? WHERE report_id = ?";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was an error preparing the updateReport query : " . $this->db->error);
        
        $statement->bind_param('si', $newStatus, $reportId);

        if (!$statement->execute())
            throw new Exception("Failed to update report : " . $statement->error);

        $statement->close();
    }


    public function updateReportStatusWithNotes(int $reportId, string $newStatus, string $notes)
    {
        $query = "UPDATE report SET report_status = ?, admin_notes = ? WHERE report_id = ?";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was an eror preparing the updateReportStatusWithNotes query : " . $this->db->error);

        $statement->bind_param('ssi', $newStatus, $notes, $reportId);

        if (!$statement->execute())
            throw new Exception("Failed to update report status with notes : " . $statement->error);

        $statement->close();
    }

}