# -*- coding: utf-8 -*-
from selenium import webdriver
from selenium.webdriver.chrome.service import Service

service = Service()
options = webdriver.ChromeOptions()
driver = webdriver.Chrome(service=service, options=options)
driver.get("https://twitter.com/i/flow/login")

from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from time import sleep

#setup login
sleep(3)

# Use single quotes for the XPath string
username = driver.find_element(By.XPATH, '//input[@name="text"]')
username.send_keys("@cw1ldf")

# Locate the "Suivant" button and click it
sui = driver.find_element(By.XPATH, '//span[contains(text(), "Suivant")]')
sui.click()

#password
sleep(3)
pw = driver.find_element(By.XPATH, '//input[@name="password"]')
pw.send_keys("chaymae2003")
conn = driver.find_element(By.XPATH, '//span[contains(text(), "Se connecter")]')
conn.click()

#search and fetch
sleep(3)
search_box=driver.find_element(By.XPATH,'//input[@data-testid="SearchBox_Search_Input"]')
search_box.send_keys("tesla")
search_box.send_keys(Keys.ENTER)



article=driver.find_elements(By.XPATH,'//*[@data-testid="tweet"]')
users=[]
time=[]
tweets=[]
retweet=[]
reply=[]
liikes=[]


# Iterate over the loop to fetch tweets
while len(tweets)<100:
    article = driver.find_elements(By.XPATH, '//*[@data-testid="tweet"]')
    for art in article:
        # Extract tweet data
        usertag = art.find_element(By.XPATH, '//*[@data-testid="User-Name"]').text
        date = art.find_element(By.XPATH, '//time').get_attribute("datetime")
        tweet_text = art.find_element(By.XPATH, '//*[@data-testid="tweetText"]').text
        replies = art.find_element(By.XPATH, '//*[@data-testid="reply"]').text
        retweets = art.find_element(By.XPATH, '//*[@data-testid="retweet"]').text
        likes = art.find_element(By.XPATH, '//*[@data-testid="like"]').text
        
            
        # Append the data to the respective lists
        users.append(usertag)
        time.append(date)
        tweets.append(tweet_text)
        reply.append(replies)
        retweet.append(retweets)
        liikes.append(likes)
    
    # Scroll down to load more tweets
    driver.execute_script('window.scrollTo(0, document.body.scrollHeight);')
    # Refresh the list of articles
    article = driver.find_elements(By.XPATH, '//*[@data-testid="tweet"]')
    
    # Break the loop if the condition is met
    if len(tweets) > 50:
        break
print(len(tweets))
print(tweets)
