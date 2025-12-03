<?php
header('Content-Type: application/json');

$channelId = $_GET['channel_id'] ?? 'UCw4TrxqCpuJiW3bLCFQR3UA';
$url = "https://www.youtube.com/feeds/videos.xml?channel_id=$channelId";

try {
    $xml = simplexml_load_file($url);
    $entries = $xml->entry;
    
    $lastLive = null;
    $lastVideo = null;

    // Procurar por lives ativas primeiro
    foreach ($entries as $entry) {
        $liveStatus = (string)$entry->children('yt', true)->liveBroadcastContent;
        $isLive = ($liveStatus === 'live');
        $isUpload = ((string)$entry->children('media', true)->group->content->attributes()->type === 'video/upload');
        
        // Se for live ativa
        if ($isLive) {
            $lastLive = [
                'id' => (string)$entry->children('yt', true)->videoId,
                'isLive' => true
            ];
            break;
        }
        
        // Se for uma transmissão gravada (live passada)
        if (!$isUpload && !$lastLive) {
            $lastLive = [
                'id' => (string)$entry->children('yt', true)->videoId,
                'isLive' => false
            ];
        }
        
        // Último vídeo normal
        if (!$lastVideo) {
            $lastVideo = [
                'id' => (string)$entry->children('yt', true)->videoId,
                'isLive' => false
            ];
        }
    }

    // Prioridade: live ativa > live gravada > último vídeo
    $result = $lastLive ?? $lastVideo;

    if (!$result) throw new Exception('Nenhum vídeo encontrado');
    
    echo json_encode($result);

} catch (Exception $e) {
    echo json_encode(['error' => 'Não foi possível carregar os vídeos']);
}
?>