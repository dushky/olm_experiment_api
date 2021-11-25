import os
import time 



for i in range(1,10):
    f = open("demofile2.txt", "a")
    f.write(str(i))
    f.close()
    time.sleep(2)
