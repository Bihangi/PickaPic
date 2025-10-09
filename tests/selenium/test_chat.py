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
BASE_URL = "http://127.0.0.1:8000/chat?verified=true"
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

def test_chat_page():
    driver = setup_driver()
    wait = WebDriverWait(driver, 20)  # Give enough time for page & content
    test_results = []

    try:
        # ----- Navigate to Chat Page -----
        logger.info("Navigating to Chat page...")
        driver.get(BASE_URL)
        wait.until(EC.visibility_of_element_located((By.TAG_NAME, "body")))
        test_results.append(("Page Load", "PASS", "Chat page loaded successfully"))

        # ----- Conversations List -----
        try:
            # Wait up to 10 seconds for conversations or empty state
            wait.until(lambda d: len(d.find_elements(By.CSS_SELECTOR, ".conversation-item")) > 0
                       or d.find_element(By.CSS_SELECTOR, "div.text-center.py-12"))
            convos = driver.find_elements(By.CSS_SELECTOR, ".conversation-item")
            if convos:
                first_convo = convos[0]
                convo_text = first_convo.text.strip()[:50]
                logger.info(f"Found conversation: {convo_text}")
                test_results.append(("Conversations List", "PASS", f"{len(convos)} conversation(s) found"))
            else:
                empty_state = driver.find_element(By.CSS_SELECTOR, "div.text-center.py-12")
                assert "No conversations yet" in empty_state.text
                test_results.append(("Conversations List", "WARN", "No conversations yet"))
        except Exception as e:
            test_results.append(("Conversations List", "FAIL", str(e)))

        # ----- Message Input Box -----
        try:
            message_input = wait.until(EC.visibility_of_element_located((By.ID, "message-input")))
            message_input.clear()
            message_input.send_keys("Hello from Selenium test")
            # Wait a moment to simulate typing
            time.sleep(1)
            assert "Hello" in message_input.get_attribute("value")
            test_results.append(("Message Input", "PASS", "Message typed into chat box"))
        except Exception as e:
            test_results.append(("Message Input", "FAIL", str(e)))

        # ----- Send Button -----
        try:
            send_btn = wait.until(EC.element_to_be_clickable((By.ID, "send-button")))
            assert send_btn.is_enabled()
            test_results.append(("Send Button", "PASS", "Send button available"))
        except Exception as e:
            test_results.append(("Send Button", "FAIL", str(e)))
            

    except Exception as e:
        logger.error(f"Unexpected error: {e}")
        test_results.append(("General", "FAIL", str(e)))

    finally:
        # ----- Give user time to see result before closing -----
        time.sleep(5)
        driver.quit()

        # ----- Print Test Results -----
        print("\n" + "="*60)
        print("CHAT PAGE TEST RESULTS")
        print("="*60)

        passed = failed = warned = 0
        for test_name, status, details in test_results:
            status_symbol = "✓" if status == "PASS" else "✗" if status == "FAIL" else "⚠"
            print(f"{status_symbol} {test_name}: {status} - {details}")

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
    success = test_chat_page()
    exit(0 if success else 1)
