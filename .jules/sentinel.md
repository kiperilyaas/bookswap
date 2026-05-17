## 2025-05-15 - [IDOR in Resource Deletion]
**Vulnerability:** Resource deletion endpoints (`ListingsController::deleteListing` and `UserController::deleteUser`) were accepting IDs via `GET` or `POST` without verifying that the resource belonged to the currently authenticated user.
**Learning:** In a multi-user environment, simply knowing a resource ID is often sufficient for unauthorized access or deletion if server-side ownership checks are missing.
**Prevention:** Always verify that the `id_seller` (for listings) or `id_user` (for user profiles) matches the `$_SESSION['id_user']` before performing destructive operations. Additionally, use `POST` for state-changing actions.
