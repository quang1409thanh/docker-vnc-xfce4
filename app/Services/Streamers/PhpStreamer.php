<?php

namespace App\Services\Streamers;

use DaveRandom\Resume\FileResource;
use DaveRandom\Resume\InvalidRangeHeaderException;
use DaveRandom\Resume\NonExistentFileException;
use DaveRandom\Resume\RangeSet;
use DaveRandom\Resume\ResourceServlet;
use DaveRandom\Resume\SendFileFailureException;
use DaveRandom\Resume\UnreadableFileException;
use DaveRandom\Resume\UnsatisfiableRangeException;
use Symfony\Component\HttpFoundation\Response;
use function DaveRandom\Resume\get_request_header;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PhpStreamer extends Streamer implements DirectStreamerInterface
{
    public function stream()
    {
        try {
            $rangeHeader = get_request_header('Range');

            // On Safari, "Range" header value can be "bytes=0-1" which breaks streaming.
            $rangeHeader = $rangeHeader === 'bytes=0-1' ? 'bytes=0-' : $rangeHeader;

            $rangeSet = RangeSet::createFromHeader($rangeHeader);

            // Chuyển đổi tệp sang định dạng webm
            $outputPath = $this->convertToWebm($this->song->path);
            if (file_exists($outputPath)) {
                $response = new BinaryFileResponse($outputPath);
                BinaryFileResponse::trustXSendfileTypeHeader();
                return $response;
            } else {
                abort(404);
            }
        } catch (InvalidRangeHeaderException) {
            abort(Response::HTTP_BAD_REQUEST);
        } catch (UnsatisfiableRangeException) {
            abort(Response::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE);
        } catch (NonExistentFileException) {
            abort(Response::HTTP_NOT_FOUND);
        } catch (UnreadableFileException) {
            abort(Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (SendFileFailureException $e) {
            abort_unless(headers_sent(), Response::HTTP_INTERNAL_SERVER_ERROR);
            echo "An error occurred while attempting to send the requested resource: {$e->getMessage()}";
        }

        exit;
    }

    private function convertToWebm(string $inputPath): string


    {
        $filename = 'myText.txt';
        file_put_contents($filename, $inputPath);
        $escapedInputPath = escapeshellarg($filename);

        // Lấy tên tệp từ đường dẫn đầu vào và thêm phần mở rộng .webm
        $outputPath = pathinfo($inputPath, PATHINFO_DIRNAME) . '/' . pathinfo($inputPath, PATHINFO_FILENAME) . '.webm';

        // Thoát ra đúng cách đường dẫn đầu vào và đầu ra
        $escapedInputPath = escapeshellarg($inputPath);
        $escapedOutputPath = escapeshellarg($outputPath);

        exec("ffmpeg -i {$escapedInputPath} -c:v libvpx -c:a libvorbis {$escapedOutputPath}");

        return $outputPath;
    }
}
