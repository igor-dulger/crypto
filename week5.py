import sys
import numbthy
import datetime

p=13407807929942597099574024998205846127479365820592393377723561443721764030073546976801874298166903427690031858186486050853753882811946569946433649006084171
g=11717829880366207009516117596335367088558084999998952205599979459063929499736583746670572176471460312928594829675428279466566527115212748467589894601965568
h=3239475104050450443565264378728065788649097520952449527834792452971981976143292558073856937958553180532878928001494706097394108577585732452307673444020333
B=pow(2, 20)

#p=13
#g=2
#h=5
#B=pow(2, 2)

#5 base 2 in Z13 = 9
#x0 = 2
#x1 = 1

#p=1073676287
#g=1010343267
#h=857348958
#B=pow(2, 10)

#1026831 (*this is the solution*)
#x0 = 1002;
#x1 = 783;

print datetime.datetime.now()

gi=numbthy.inverse_mod(g, p)
gPowB=numbthy.power_mod(g, B, p)

print "p", p
print "g", g
print "h", h
print "B", B
print "inverse g", gi
print "gPowB", gPowB

left = {}

powX1 = h % p
left[powX1] = 0
x1 = 1

while(x1 < B):
    powX1=(powX1*gi) % p
    left[powX1] = x1

#    left[(h*numbthy.power_mod(gi, x1, p)) % p] = x1
    x1=x1+1
    if (x1 % 100000 == 0) :
        print x1

x0 = 0
while(x0 < B):
    if x0 == 0:
        right = 1
    else :
        right=right*gPowB % p

#    right = numbthy.power_mod(gPowB, x0, p)
    if left.get(right, -1) != -1:
        print "Result x0", x0, "x1", left.get(right, -1)
        print "x =", left.get(right, -1) + B*x0
        break
    x0=x0+1
print datetime.datetime.now()
