## 2025-05-14 - [IDOR in Listing Deletion]
**Vulnerability:** Any logged-in user could delete any listing by navigating to `index.php?table=Listings&action=deleteListing&id=<ID>`.
**Learning:** The application was using GET requests for destructive actions and lacked ownership verification on the server side.
**Prevention:** Always use POST for state-changing actions and verify that the resource belongs to the authenticated user before performing the operation.
