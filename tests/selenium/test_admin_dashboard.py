from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
from selenium.common.exceptions import TimeoutException, NoSuchElementException
import time
import logging

# ----- Configuration -----
BASE_URL = "http://127.0.0.1:8000/admin"
CHROME_DRIVER_PATH = r"D:\Drivers\chromedriver-win64\chromedriver.exe"

# ----- Setup Logging -----
logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')
logger = logging.getLogger(__name__)

# ----- Setup WebDriver -----
def setup_driver():
    options = Options()
    options.add_argument("--start-maximized")
    options.add_argument("--disable-blink-features=AutomationControlled")
    options.add_experimental_option("excludeSwitches", ["enable-automation"])
    options.add_experimental_option('useAutomationExtension', False)
    driver = webdriver.Chrome(service=Service(CHROME_DRIVER_PATH), options=options)
    driver.execute_script("Object.defineProperty(navigator, 'webdriver', {get: () => undefined})")
    return driver

# ----- Main Test -----
def test_admin_dashboard_ui():
    driver = setup_driver()
    wait = WebDriverWait(driver, 25)  # longer wait to avoid racing
    test_results = []

    try:
        # ----- Page Load -----
        try:
            logger.info("Navigating to Admin Dashboard")
            driver.get(BASE_URL)
            wait.until(EC.presence_of_element_located((By.TAG_NAME, "body")))
            time.sleep(1)  # small pause
            test_results.append(("Page Load", "PASS", "Admin Dashboard loaded"))
        except Exception as e:
            test_results.append(("Page Load", "FAIL", str(e)))

        # ----- Sidebar Links -----
        sidebar_links = ["Dashboard", "Users", "Photographers", "Bookings", "Reviews", "Reports", "Pending"]
        for link_text in sidebar_links:
            try:
                link = wait.until(EC.presence_of_element_located(
                    (By.XPATH, f"//a[contains(normalize-space(), '{link_text}')]")
                ))
                assert link.is_displayed() and link.is_enabled(), f"{link_text} not clickable"
                test_results.append((f"Sidebar Link: {link_text}", "PASS", f"{link_text} visible and clickable"))
                time.sleep(0.3)
            except Exception as e:
                test_results.append((f"Sidebar Link: {link_text}", "FAIL", str(e)))

        # ----- Key Metrics -----
        metrics = {
            "Total Users": ".grid > div:nth-child(1) p.text-3xl",
            "Total Photographers": ".grid > div:nth-child(2) p.text-3xl",
            "Total Bookings": ".grid > div:nth-child(3) p.text-3xl"
        }
        for metric_name, selector in metrics.items():
            try:
                elem = wait.until(EC.presence_of_element_located((By.CSS_SELECTOR, selector)))
                assert elem.text.strip() != "", f"{metric_name} empty"
                test_results.append((metric_name, "PASS", f"Value: {elem.text.strip()}"))
                time.sleep(0.3)
            except Exception as e:
                test_results.append((metric_name, "FAIL", str(e)))

        # ----- Recent Bookings -----
        try:
            recent_bookings = driver.find_elements(
                By.CSS_SELECTOR, "div[aria-label='Recent Bookings'] div.flex.items-center.justify-between"
            )
            test_results.append(("Recent Bookings", "PASS", f"Found {len(recent_bookings)} recent bookings"))
            time.sleep(0.3)
        except Exception as e:
            test_results.append(("Recent Bookings", "FAIL", str(e)))

        # ----- Quick Actions -----
        try:
            quick_actions = driver.find_elements(By.CSS_SELECTOR, "div.mt-8 div.grid a")
            test_results.append(("Quick Actions", "PASS", f"Found {len(quick_actions)} quick action buttons"))
            time.sleep(0.3)
        except Exception as e:
            test_results.append(("Quick Actions", "FAIL", str(e)))

    except Exception as e:
        logger.error(f"Unexpected error during testing: {e}")
        test_results.append(("General", "FAIL", str(e)))

    finally:
        # ----- Cleanup -----
        time.sleep(2)
        driver.quit()

        # ----- Print Results -----
        print("\n" + "="*60)
        print("ADMIN DASHBOARD UI TEST RESULTS")
        print("="*60)

        passed = failed = warned = 0
        for name, status, detail in test_results:
            symbol = "✓" if status == "PASS" else "✗" if status == "FAIL" else "⚠"
            print(f"{symbol} {name}: {status} - {detail}")
            if status == "PASS":
                passed += 1
            elif status == "FAIL":
                failed += 1
            else:
                warned += 1

        print("="*60)
        print(f"SUMMARY: {passed} passed, {failed} failed, {warned} warnings")
        print("="*60)

        return failed == 0

if __name__ == "__main__":
    success = test_admin_dashboard_ui()
    exit(0 if success else 1)
