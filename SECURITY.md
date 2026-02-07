# Security Model

## Threat model
- Account takeover: mitigated with strong password rules, optional 2FA, email verification, and auth rate limiting.
- IDOR/data leaks: mitigated with Laravel Policies/Gates and strict ownership checks.
- Payment fraud: escrow actions tied to authenticated users, dispute lifecycle, immutable audit logs.
- Malicious uploads: private storage, MIME/size validation, queued malware scan hooks.
- Abuse/harassment: block system, report system, message moderation hooks, admin moderation queue.
- PII compromise: encrypted payout details/addresses and data minimization.

## Controls
- Sanctum token auth for API with revocation support.
- CSRF protection for web and restricted CORS for API.
- Signed/private download pattern for sensitive files.
- Immutable `audit_logs` for escrow and dispute transitions.
- GDPR-friendly delete/export flow via queued jobs.
