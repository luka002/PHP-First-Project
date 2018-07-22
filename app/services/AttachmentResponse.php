<?php

/**
 * Sends attachment to the user as HTML response.
 */
class AttachmentResponse implements Response {

    /**
     * @var string Content of the attachment.
     */
    private $attachment;
    /**
     * @var string Name of the attachment.
     */
    private $fileName;

    /**
     * AttachmentResponse constructor.
     * @param string $attachment Content of the attachment.
     * @param string $fileName Name of the attachment.
     */
    public function __construct(string $attachment, string $fileName) {
        $this->attachment = $attachment;
        $this->fileName = $fileName;
    }

    /**
     * Sends attachment to the user.
     */
    public function send(): void {
        header('Content-Disposition: attachment; filename="' . $this->fileName . '"');
        header('Content-Type: text/html; charset=utf-8');
        header('Content-Length: ' . mb_strlen($this->attachment));
        header('Connection: close');

        echo $this->attachment;
    }

}