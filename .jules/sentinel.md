## 2026-05-18 - [IDOR & CSRF in Listing Deletion]
**Vulnerability:** The 'deleteListing' action was vulnerable to IDOR because it only checked for the listing ID without verifying the seller's ownership. It was also vulnerable to CSRF as it accepted GET requests.
**Learning:** Destructive actions must always use POST requests and verify resource ownership against the session user ID to prevent unauthorized access and state changes.
**Prevention:** Enforce ownership checks at the model level by including user ID in WHERE clauses and ensure controllers only process such actions via POST.
