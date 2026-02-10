# Escrow ledger implementation

## Accounts
- `buyer_receivable`
- `platform_escrow`
- `vendor_payable`

## Posting rules
1. **Capture**: debit `buyer_receivable`, credit `platform_escrow`.
2. **Release**: debit `platform_escrow`, credit `vendor_payable`, then provider transfer.
3. **Refund**: debit `platform_escrow`, credit `buyer_receivable`, then provider refund.

All transitions append to `audit_logs` with actor attribution.
