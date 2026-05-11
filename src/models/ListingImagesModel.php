<?php
defined("APP") or die("ACCESSO NEGATO");

require_once 'config/dbconnect.php';

class ListingImagesModel {
    private $pdo;

    public function __construct() {
        $this->pdo = DB::connect();
    }

    /**
     * Aggiunge un'immagine per un listing
     * @param int $id_listing - ID del listing
     * @param string $image_path - Path relativo dell'immagine
     * @param int $is_primary - 1 se è l'immagine principale, 0 altrimenti
     * @return bool
     */
    public function addImage($id_listing, $image_path, $is_primary = 0) {
        // Se questa è la principale, rimuovi il flag dalle altre
        if ($is_primary == 1) {
            $this->removePrimaryFlag($id_listing);
        }

        $sql = "INSERT INTO listing_images (id_listing, image_path, is_primary)
                VALUES (?, ?, ?)";
        $stm = $this->pdo->prepare($sql);
        $stm->execute([$id_listing, $image_path, $is_primary]);
        return $stm->rowCount() !== 0;
    }

    /**
     * Recupera tutte le immagini di un listing
     * @param int $id_listing
     * @return array
     */
    public function getImagesByListing($id_listing) {
        $sql = "SELECT * FROM listing_images
                WHERE id_listing = ?
                ORDER BY is_primary DESC, id_image ASC";
        $stm = $this->pdo->prepare($sql);
        $stm->execute([$id_listing]);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Recupera solo l'immagine principale di un listing
     * @param int $id_listing
     * @return string|null - Path dell'immagine o null
     */
    public function getPrimaryImage($id_listing) {
        $sql = "SELECT image_path FROM listing_images
                WHERE id_listing = ? AND is_primary = 1
                LIMIT 1";
        $stm = $this->pdo->prepare($sql);
        $stm->execute([$id_listing]);
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['image_path'] : null;
    }

    /**
     * Elimina un'immagine
     * @param int $id_image
     * @return bool
     */
    public function deleteImage($id_image) {
        $sql = "DELETE FROM listing_images WHERE id_image = ?";
        $stm = $this->pdo->prepare($sql);
        $stm->execute([$id_image]);
        return $stm->rowCount() !== 0;
    }

    /**
     * Imposta un'immagine come principale
     * @param int $id_image
     * @param int $id_listing
     * @return bool
     */
    public function setPrimaryImage($id_image, $id_listing) {
        // Rimuovi flag principale dalle altre
        $this->removePrimaryFlag($id_listing);

        // Imposta questa come principale
        $sql = "UPDATE listing_images SET is_primary = 1 WHERE id_image = ?";
        $stm = $this->pdo->prepare($sql);
        $stm->execute([$id_image]);
        return $stm->rowCount() !== 0;
    }

    /**
     * Rimuove il flag principale da tutte le immagini di un listing
     * @param int $id_listing
     */
    private function removePrimaryFlag($id_listing) {
        $sql = "UPDATE listing_images SET is_primary = 0 WHERE id_listing = ?";
        $stm = $this->pdo->prepare($sql);
        $stm->execute([$id_listing]);
    }

    /**
     * Conta le immagini di un listing
     * @param int $id_listing
     * @return int
     */
    public function countImages($id_listing) {
        $sql = "SELECT COUNT(*) FROM listing_images WHERE id_listing = ?";
        $stm = $this->pdo->prepare($sql);
        $stm->execute([$id_listing]);
        return (int)$stm->fetchColumn();
    }
}
