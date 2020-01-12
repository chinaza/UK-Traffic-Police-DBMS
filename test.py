product = 0
while True:
    val = int(input("Input a number! ") or 0)
    if (val == 0):
        break
    if product==0:
        product = 1
    product = product * val
    
print("The product is " + str(product))