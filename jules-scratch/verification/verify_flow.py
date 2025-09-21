import uuid
from playwright.sync_api import sync_playwright, expect

def run_verification(playwright):
    # Generate a unique email for this test run
    unique_email = f"user_{uuid.uuid4()}@example.com"
    password = "password123"

    browser = playwright.chromium.launch(headless=True)
    context = browser.new_context()
    page = context.new_page()

    try:
        # 1. Registration
        page.goto("http://localhost:8000/register.php")
        page.get_by_label("Name").fill("Test User")
        page.get_by_label("Email address").fill(unique_email)
        page.get_by_label("Password").fill(password)
        page.get_by_role("button", name="Sign up").click()
        expect(page.get_by_role("alert")).to_contain_text("Registration successful!")

        # 2. Login
        page.get_by_label("Email address").fill(unique_email)
        page.get_by_label("Password").fill(password)
        page.get_by_role("button", name="Sign in").click()
        expect(page.get_by_role("heading", name=f"Welcome, Test User!")).to_be_visible()

        # 3. Browse Products
        page.get_by_role("link", name="Products").click()
        expect(page.get_by_role("heading", name="All Products")).to_be_visible()

        # 4. View Product (assuming there's at least one product)
        # This might be brittle if there are no products. Let's add one first.
        # For now, let's assume the admin has added products. We will click the first one.
        page.locator(".card a.btn-primary").first.click()
        expect(page.get_by_role("button", name="Add to Cart")).to_be_visible()

        # 5. Add to Cart
        page.get_by_role("button", name="Add to Cart").click()
        expect(page.get_by_role("heading", name="Shopping Cart")).to_be_visible()

        # 6. Checkout
        page.get_by_role("link", name="Proceed to Checkout").click()
        expect(page.get_by_role("heading", name="Checkout")).to_be_visible()

        # 7. Place Order
        page.get_by_role("button", name="Pay").click()
        expect(page.get_by_role("heading", name="Thank You!")).to_be_visible()
        expect(page.get_by_text("Your order has been placed successfully.")).to_be_visible()

        # 8. View Orders and take screenshot
        page.get_by_role("link", name="View My Orders").click()
        expect(page.get_by_role("heading", name="My Orders")).to_be_visible()

        # Verify the order is in the table
        expect(page.locator("table tbody tr")).to_have_count(1)

        page.screenshot(path="jules-scratch/verification/verification.png")
        print("Screenshot taken successfully.")

        # 9. Logout
        page.get_by_role("link", name="Logout").click()
        expect(page.get_by_role("heading", name="Login to your Account")).to_be_visible()

    finally:
        browser.close()

with sync_playwright() as playwright:
    run_verification(playwright)
