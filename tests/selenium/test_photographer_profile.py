from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait, Select
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
from selenium.common.exceptions import TimeoutException, NoSuchElementException
import time
import logging

# ----- Configuration -----
BASE_URL = "http://localhost:8000"  
PHOTOGRAPHER_ID = 15
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

def test_photographer_profile():
    driver = setup_driver()
    wait = WebDriverWait(driver, 10)
    test_results = []

    try:
        # ----- Navigate to Photographer Profile -----
        logger.info(f"Navigating to photographer profile: {PHOTOGRAPHER_ID}")
        driver.get(f"{BASE_URL}/photographers/{PHOTOGRAPHER_ID}?verified=true")
        
        # Wait for page to load
        wait.until(EC.presence_of_element_located((By.TAG_NAME, "body")))
        test_results.append(("Page Load", "PASS", "Page loaded successfully"))

        # ----- Test Back Button -----
        try:
            back_button = wait.until(EC.element_to_be_clickable((By.CSS_SELECTOR, "a[href*='photographers']")))
            assert back_button.is_displayed(), "Back button not visible"
            test_results.append(("Back Button", "PASS", "Back button is visible and clickable"))
        except (TimeoutException, AssertionError) as e:
            test_results.append(("Back Button", "FAIL", str(e)))

        # ----- Check Profile Image -----
        try:
            profile_img = driver.find_element(By.CSS_SELECTOR, "img[alt]")
            img_src = profile_img.get_attribute("src")
            assert img_src and img_src != "", "Profile image missing"
            test_results.append(("Profile Image", "PASS", f"Image source: {img_src[:50]}..."))
        except (NoSuchElementException, AssertionError) as e:
            test_results.append(("Profile Image", "FAIL", str(e)))

        # ----- Check Name and Location -----
        try:
            name = driver.find_element(By.CSS_SELECTOR, "h1.text-3xl")
            location = driver.find_element(By.CSS_SELECTOR, "div.absolute.-bottom-3")
            photographer_name = name.text.strip()
            photographer_location = location.text.strip()
            
            assert photographer_name, "Photographer name is empty"
            assert photographer_location, "Photographer location is empty"
            
            logger.info(f"Photographer: {photographer_name} | Location: {photographer_location}")
            test_results.append(("Name & Location", "PASS", f"{photographer_name} - {photographer_location}"))
        except (NoSuchElementException, AssertionError) as e:
            test_results.append(("Name & Location", "FAIL", str(e)))

        # ----- Check Specialties -----
        try:
            specialties = driver.find_elements(By.CSS_SELECTOR, "div.flex.flex-wrap span")
            specialty_texts = [s.text.strip() for s in specialties if s.text.strip()]
            
            if specialty_texts:
                logger.info(f"Specialties: {specialty_texts}")
                test_results.append(("Specialties", "PASS", f"Found {len(specialty_texts)} specialties"))
            else:
                test_results.append(("Specialties", "WARN", "No specialties found"))
        except Exception as e:
            test_results.append(("Specialties", "FAIL", str(e)))

        # ----- Check Packages -----
        try:
            packages = driver.find_elements(By.CSS_SELECTOR, "div.group.mb-6")
            logger.info(f"Found {len(packages)} packages")
            
            if packages:
                first_pkg_name = packages[0].find_element(By.CSS_SELECTOR, "h3.text-lg").text.strip()
                first_pkg_price = packages[0].find_element(By.CSS_SELECTOR, "div.text-xl").text.strip()
                logger.info(f"First package: {first_pkg_name} | Price: {first_pkg_price}")
                test_results.append(("Packages", "PASS", f"{len(packages)} packages found"))
            else:
                test_results.append(("Packages", "WARN", "No packages found"))
        except Exception as e:
            test_results.append(("Packages", "FAIL", str(e)))

        # ----- Test Review Form -----
        try:
            # Check if review form exists
            display_name_input = wait.until(EC.presence_of_element_located((By.NAME, "display_name")))
            rating_select_element = driver.find_element(By.NAME, "rating")
            comment_textarea = driver.find_element(By.NAME, "comment")
            anonymous_checkbox = driver.find_element(By.ID, "anonymous")
            
            # Fill out the form
            display_name_input.clear()
            display_name_input.send_keys("Test User")
            
            rating_select = Select(rating_select_element)
            rating_select.select_by_value("5")
            
            comment_textarea.clear()
            comment_textarea.send_keys("This is an automated test review.")
            
            # Optional: Check anonymous checkbox
            if not anonymous_checkbox.is_selected():
                anonymous_checkbox.click()
            
            # Look for submit button
            submit_button = driver.find_element(By.CSS_SELECTOR, "button[type='submit'], input[type='submit']")
            
            # Scroll submit button into view
            driver.execute_script("arguments[0].scrollIntoView(true);", submit_button)
            time.sleep(1)
            
            # Verify form is filled
            assert display_name_input.get_attribute("value") == "Test User"
            assert rating_select.first_selected_option.get_attribute("value") == "5"
            assert comment_textarea.get_attribute("value") == "This is an automated test review."
            
            test_results.append(("Review Form", "PASS", "Form filled successfully"))
            
            # Uncomment the next line to actually submit the review
            # submit_button.click()
            logger.info("Review form filled but not submitted (uncomment to submit)")
            
        except Exception as e:
            test_results.append(("Review Form", "FAIL", str(e)))

        # ----- Test Back Button Functionality -----
        try:
            back_button = driver.find_element(By.CSS_SELECTOR, "a[href*='photographers']")
            original_url = driver.current_url
            
            # Click back button
            back_button.click()
            time.sleep(2)
            
            # Check if URL changed
            new_url = driver.current_url
            if new_url != original_url:
                test_results.append(("Back Navigation", "PASS", f"Navigated from profile to: {new_url}"))
            else:
                test_results.append(("Back Navigation", "FAIL", "URL did not change after clicking back"))
                
        except Exception as e:
            test_results.append(("Back Navigation", "FAIL", str(e)))

    except Exception as e:
        logger.error(f"Unexpected error during testing: {e}")
        test_results.append(("General", "FAIL", str(e)))

    finally:
        time.sleep(2)
        driver.quit()
        
        # ----- Print Test Results -----
        print("\n" + "="*60)
        print("PHOTOGRAPHER PROFILE TEST RESULTS")
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
        
        return failed == 0  # Return True if all tests passed

if __name__ == "__main__":
    success = test_photographer_profile()
    exit(0 if success else 1)