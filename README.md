
1. **User Mobile App:**

   A. **Home Screen:**
   - Display user's name and profile picture.
   - Show current balance (e.g., BDT 0.00).
   - Include a welcome message and "Start Earning Now" button.
   - Implement a card view with:
     - Total Earnings
     - Ads Watched
     - Your Referrals

   B. **Earn Rewards Screen:**
   - Show "Today's Progress" with a progress bar indicating completed daily tasks.
   - Include a "Watch Ad & Earn" button to initiate ad viewing.
   - Display a bar chart representing weekly activity, detailing completed tasks by day.

   C. **Ad Viewing Screen:**
   - Show a timer (e.g., 15 seconds) at the top.
   - Display an ad from the Monetag network.
   - Enable "Click to get the reward!" and "Continue" buttons post-timer.
   - Reward users with a specified amount upon successful ad viewing.

   D. **Referral Program Screen:**
   - Generate a unique referral link for users.
   - Provide a button to copy and share the link via Telegram.
   - Display total successful referrals attributed to the user.

   E. **Withdraw Funds Screen:**
   - Show "Available Balance".
   - Include a form for New Withdrawal Requests with:
     - Method (dropdown for payment selection)
     - Amount field
     - Address/Number (for wallet or mobile number)
   - Implement a "Submit Request" button.

   F. **Profile Screen:**
   - Display user's profile picture, name, and username.
   - Include:
     - Current Balance
     - Total Earnings
     - Total Ads Watched
     - Total Referrals

   G. **Navigation:**
   - Implement a bottom navigation bar with Home, Earn, Refer, Withdraw, and Profile menus.

2. **Web-based Admin Panel:**

   A. **Dashboard:**
   - Provide a card view summarizing:
     - Total Users and last signup time
     - Tasks Completed
     - Active Withdraw Methods
     - Total Withdraws (total amount withdrawn, e.g., 100 USD)

   B. **User Management:**
   - List all registered users with search, block, and unblock functionalities.
   - Allow balance adjustment for any user.

   C. **Withdraws Management:**
   - Display a list of withdrawal requests (Pending, Approved, Rejected).
   - Enable admin to approve or reject requests.

   D. **Withdraw Methods:**
   - Allow admin to add, delete, or edit payment methods (e.g., bKash, Nagad, Recharge).

   E. **Application Settings:**
   - Provide controls for app functionality, including:
     - Ads Reward (amount earned per ad)
     - Daily Ads Limit (maximum ads per day)
     - Refer Bonus (%) for referrer
     - Currency Symbol (e.g., BDT, USD)
     - Monetag Site ID for integration
   - Include an "Update Settings" button for changes.

**Instructions for AI:**
- Develop an intuitive user interface for both the mobile app and admin panel.
- Ensure security protocols for user data and transaction processing.
- Optimize the app for performance and scalability.
- Implement analytics to track user engagement and ad performance.
- Provide comprehensive user support and FAQs within the app.
