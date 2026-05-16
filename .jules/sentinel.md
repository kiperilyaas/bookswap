## 2025-05-23 - [RCE via Unrestricted File Upload]
**Vulnerability:** The `handleImageUpload` function in `utils/imageUpload.php` was vulnerable to Remote Code Execution (RCE). While it validated the MIME type using `finfo`, it relied on the user-provided filename extension when saving the file. An attacker could bypass MIME checks (e.g., by using a polyglot file) and still have the file saved with a `.php` extension.
**Learning:** Validating MIME type is not enough if the file extension is still derived from untrusted user input.
**Prevention:** Always generate the file extension based on the validated MIME type or a whitelist of allowed extensions. Use a mapping of MIME types to extensions.

## 2025-05-23 - [IDOR and CSRF in Deletion Actions]
**Vulnerability:** The `deleteListing` action in `ListingsController` was accessible via a `GET` request and lacked ownership verification. This allowed an attacker to delete any user's listing by just knowing (or guessing) the listing ID (IDOR), and could be triggered by tricking a logged-in user into clicking a link or loading a malicious image (CSRF).
**Learning:** Destructive actions should never use `GET` and must always verify that the resource belongs to the authenticated user.
**Prevention:** Migrate destructive actions to `POST` and implement server-side ownership checks by comparing the resource's owner ID with the session's user ID.
