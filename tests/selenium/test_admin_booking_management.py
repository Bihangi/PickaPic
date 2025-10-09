from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
import time
import logging

# ----- Configuration -----
BASE_URL = "http://127.0.0.1:8000/admin/bookings"
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
def test_booking_management_ui():
    driver = setup_driver()
    wait = WebDriverWait(driver, 20)
    test_results = []

    try:
        # ----- Page Load -----
        try:
            logger.info("Navigating to Booking Management page")
            driver.get(BASE_URL)
            wait.until(EC.presence_of_element_located((By.TAG_NAME, "body")))
            time.sleep(1)
            test_results.append(("Page Load", "PASS", "Booking Management page loaded"))
        except Exception as e:
            test_results.append(("Page Load", "FAIL", str(e)))

        # ----- Stats Cards -----
        stats_cards = ["Total", "Pending", "Confirmed", "Completed", "Cancelled"]
        for card in stats_cards:
            try:
                elem = wait.until(EC.presence_of_element_located(
                    (By.XPATH, f"//div[contains(@class,'grid')]//div[.//div[text()='{card}']]//div[contains(@class,'text-2xl')]")
                ))
                assert elem.text.strip() != "", f"{card} value empty"
                test_results.append((f"Stats Card: {card}", "PASS", f"Value: {elem.text.strip()}"))
                time.sleep(0.2)
            except Exception as e:
                test_results.append((f"Stats Card: {card}", "FAIL", str(e)))

        # ----- Filters -----
        filters = ["status", "date_from", "date_to"]  
        for filt in filters:
            try:
                elem = driver.find_element(By.NAME, filt)
                assert elem.is_displayed() and elem.is_enabled(), f"{filt} filter not visible or disabled"
                test_results.append((f"Filter: {filt}", "PASS", "Visible and usable"))
                time.sleep(0.2)
            except Exception as e:
                test_results.append((f"Filter: {filt}", "FAIL", str(e)))


        # ----- Table Headers -----
        headers = ["ID", "Client", "Photographer", "Event Date", "Price", "Status", "Created", "Action"]
        for header in headers:
            try:
                elem = driver.find_element(By.XPATH, f"//table//th[contains(text(),'{header}')]")
                assert elem.is_displayed(), f"{header} header not visible"
                test_results.append((f"Table Header: {header}", "PASS", "Visible"))
                time.sleep(0.1)
            except Exception as e:
                test_results.append((f"Table Header: {header}", "FAIL", str(e)))

        # ----- At Least One Booking Row -----
        try:
            rows = driver.find_elements(By.XPATH, "//table/tbody/tr")
            assert len(rows) > 0, "No booking rows found"
            test_results.append(("Booking Rows", "PASS", f"Found {len(rows)} rows"))
        except Exception as e:
            test_results.append(("Booking Rows", "FAIL", str(e)))

        # ----- Status Dropdowns -----
        try:
            dropdowns = driver.find_elements(By.XPATH, "//table/tbody/tr//select[@name='status']")
            assert len(dropdowns) > 0, "No status dropdowns found"
            test_results.append(("Status Dropdowns", "PASS", f"Found {len(dropdowns)} dropdowns"))
        except Exception as e:
            test_results.append(("Status Dropdowns", "FAIL", str(e)))

    except Exception as e:
        logger.error(f"Unexpected error: {e}")
        test_results.append(("General", "FAIL", str(e)))

    finally:
        time.sleep(2)
        driver.quit()

        # ----- Print Results -----
        print("\n" + "="*60)
        print("BOOKING MANAGEMENT UI TEST RESULTS")
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
    success = test_booking_management_ui()
    exit(0 if success else 1)
