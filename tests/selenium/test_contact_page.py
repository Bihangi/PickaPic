from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
import time
import logging

# ----- Configuration -----
BASE_URL = "http://127.0.0.1:8000/contact?verified=true#"
CHROME_DRIVER_PATH = r"D:\Drivers\chromedriver-win64\chromedriver.exe"

# ----- Logging -----
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
def test_contact_page_ui():
    driver = setup_driver()
    wait = WebDriverWait(driver, 20)
    test_results = []

    try:
        # ----- Page Load -----
        try:
            logger.info("Navigating to Contact Page")
            driver.get(BASE_URL)
            wait.until(EC.presence_of_element_located((By.TAG_NAME, "body")))
            time.sleep(1)
            test_results.append(("Page Load", "PASS", "Contact page loaded"))
        except Exception as e:
            test_results.append(("Page Load", "FAIL", str(e)))

        # ----- Form Fields -----
        form_fields = ["name", "email", "subject", "message"]
        for field in form_fields:
            try:
                elem = wait.until(EC.presence_of_element_located((By.NAME, field)))
                assert elem.is_displayed() and elem.is_enabled(), f"{field} field not interactable"
                test_results.append((f"Form Field: {field}", "PASS", f"{field} visible and editable"))
            except Exception as e:
                test_results.append((f"Form Field: {field}", "FAIL", str(e)))


        # ----- Contact Info Blocks -----
        info_blocks = ["Address", "Phone", "Email", "Follow Us"]
        for block in info_blocks:
            try:
                elem = wait.until(EC.presence_of_element_located((By.XPATH, f"//h3[contains(text(),'{block}')]")))
                assert elem.is_displayed(), f"{block} block not visible"
                test_results.append((f"Contact Info: {block}", "PASS", f"{block} block visible"))
            except Exception as e:
                test_results.append((f"Contact Info: {block}", "FAIL", str(e)))

    except Exception as e:
        logger.error(f"Unexpected error during testing: {e}")
        test_results.append(("General", "FAIL", str(e)))

    finally:
        # ----- Cleanup -----
        time.sleep(2)
        driver.quit()

        # ----- Print Results -----
        print("\n" + "="*60)
        print("CONTACT PAGE UI TEST RESULTS")
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
    success = test_contact_page_ui()
    exit(0 if success else 1)
