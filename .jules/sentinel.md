## 2026-05-18 - [IDOR in Order State Changes]
**Vulnerability:** Insecure Direct Object Reference (IDOR) in OrderController.
**Learning:** State changes for orders were performing updates based solely on user-provided order IDs without verifying if the authenticated user was actually the buyer or seller of the order.
**Prevention:** Always include ownership criteria (e.g., `AND id_user = ?`) in the SQL WHERE clause when performing operations on resources that belong to specific users.
